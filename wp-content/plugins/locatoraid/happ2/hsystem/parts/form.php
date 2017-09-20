<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class _HC_Form extends _HC_MVC
{
	protected $inputs = array();
	protected $errors = array();
	protected $orphan_errors = array();
	protected $readonly = FALSE;

	protected $children_order = array();
	protected $validators = array();

	public function add_validator( $validator )
	{
		$this->validators[] = $validator;
		return $this;
	}

	public function render()
	{
		$out = $this->make('/html/view/container');

		$inputs = $this->inputs();
		foreach( $inputs as $input_name => $input ){
			$input_view = $this
				->render_input( $input_name )
				;
			$out
				->add( $input_view )
				;
		}
		return $out;
	}

	public function render_input( $input_name )
	{
		$return = NULL;
		$input = $this->input( $input_name );
		if( ! $input ){
			return $return;
		}

		$input_view = $this->make('/html/view/label-input')
			->set_label( $input->label() )
			->set_content( $input )
			->set_error( $input->error() )
			;
		return $input_view;
	}

	public function validate()
	{
		$return = TRUE;
		$errors = array();

		$validators = array();
		if( $this->validators ){
			$values = $this->values();

			foreach( $this->validators as $validator ){
				$validation_method_name = 'prepare';
				if( is_array($validator) ){
					list( $validator, $validation_method_name ) = $validator;
				}

				$this_validators = $validator->run($validation_method_name, $values);
				$validators = array_merge( $validators, $this_validators );
			}
		}

	// inputs
		$inputs = $this->inputs();

		foreach( $inputs as $k => $input ){
			if( isset($validators[$k]) ){
				foreach( $validators[$k] as $validator_handle => $validator_args ){
					$input = call_user_func_array( array($input, 'add_validator'), $validator_args );
				}
			}

			$input_validate = $input->validate();
			if( $input_validate !== TRUE ){
				$errors[ $k ] = $input_validate;
				$return = FALSE;
			}
		}
		if( $errors ){
			$this->add_errors( $errors );
		}

		return $return;
	}

	public function set_child_order( $child_key, $order )
	{
		$this->children_order[ $child_key ] = $order;
		return $this;
	}

	public function to_model( $values )
	{
		return $values;
	}

	public function from_model( $values )
	{
		return $values;
	}

	public function reset_inputs()
	{
		$this->inputs = array();
		return $this;
	}

	function set_readonly( $ro = TRUE )
	{
		$this->readonly = $ro;
		return $this;
	}

	function readonly()
	{
		$return = TRUE;

		if( ! $this->readonly ){
			// also check all inputs
			foreach( $this->inputs as $name => $input ){
				if( ! $input->readonly() ){
					$return = FALSE;
					break;
				}
			}
		}

		return $return;
	}

	public function conf()
	{
		$return = array();
		return $return;
	}

	public function inputs()
	{
		$return = array();

		if( ! $this->inputs ){
			$inputs = $this->conf();
			foreach( $inputs as $k => $v ){
				$this
					->set_input( $k, $v )
					;
			}
		}

		$names = array_keys($this->inputs);
		if( $this->children_order ){
			$rex_order = 10;
			foreach( $names as $k ){
				if( isset($this->children_order[$k]) ){
					$this_order = $this->children_order[$k];
				}
				else {
					$this_order = $rex_order += 10;
				}
				$sort[ $k ] = $this_order;
			}
			asort($sort);
			$names = array_keys($sort);
		}

		foreach( $names as $name ){
			$input = $this->input($name);
			if( $input ){
				if( $this->orphan_errors && isset($this->orphan_errors[$name]) ){
					$input->set_error($this->orphan_errors[$name]);
					unset($this->orphan_errors[$name]);
				}
				$return[$name] = $input;
			}
		}
		return $return;
	}

	public function set_input( $name, $input )
	{
		$this->inputs[ $name ] = $input->set_name($name);
		return $this;
	}

	public function remove_input( $name ){
		return $this->unset_input( $name );
	}

	public function unset_input( $name )
	{
		unset( $this->inputs[$name] );
		return $this;
	}

	public function exists( $name )
	{
		$inputs = $this->inputs();
		return isset($inputs[$name]);
	}

	function input( $name )
	{
		$return = isset($this->inputs[$name]) ? $this->inputs[$name] : NULL;
		if( $return && $this->readonly() ){
			$return->set_readonly();
		}

		return $return;
 	}

	function grab( $post )
	{
		$inputs = $this->inputs();

		foreach( $inputs as $k => $input ){
			if( $input->readonly() ){
				continue;
			}
			$input->grab( $post );
		}

		return $this;
	}

	public function set_value( $k, $v )
	{
		$inputs = $this->inputs();

		if( array_key_exists($k, $inputs) ){
			$inputs[$k]->set_value($v);
		}

		return $this;
	}

	function set_values( $values )
	{
		$inputs = $this->inputs();

		foreach( $inputs as $k => $input ){
			if( isset($values[$k]) ){
				$input->set_value( $values[$k] );
			}
		}

		return $this;
	}

	public function value($k)
	{
		$return = NULL;
		$inputs = $this->inputs();

		if( isset($inputs[$k]) ){
			$return = $inputs[$k]->value();
		}

		return $return;
	}

	function values()
	{
		$return = array();
		$inputs = $this->inputs();

		foreach( array_keys($inputs) as $k ){
			$value = $inputs[$k]->value();
			if( ! ( ($value === NULL) OR $inputs[$k]->readonly() ) ){
				$return[$k] = $inputs[$k]->value();
			}
		}

		return $return;
	}

	public function add_errors( $errors )
	{
		if( ! is_array($errors) ){
			$errors = array($errors);
		}

		$inputs = $this->inputs();
		$input_names = array_keys($inputs);
		foreach( $errors as $k => $e ){
			if( in_array($k, $input_names) && isset($inputs[$k]) ){
				$inputs[$k]->set_error( $e );
				$this->errors[$k] = $e;
			}
			else {
				$this->orphan_errors[$k] = $e;
			}
		}
		return $this;
	}

	function set_errors( $errors )
	{
		$this->errors = array();
		$this->orphan_errors = array();

		$this->add_errors( $errors );
		return $this;
	}

	function errors()
	{
		return $this->errors;
	}

	function orphan_errors()
	{
		return $this->orphan_errors;
	}
}