<?php
/**
 * This class provides functions for converting CSS styles into inline style attributes in your HTML code.
 *
 * For more information, please see the README.md file.
 *
<<<<<<< HEAD
 * @version 1.2.0
=======
 * @version 1.0.0
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
 *
 * @author Cameron Brooks
 * @author Jaime Prado
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 * @author Roman Ožana <ozana@omdesign.cz>
<<<<<<< HEAD
 * @author Sander Kruger <s.kruger@invessel.com>
=======
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
 */
// @codingStandardsIgnoreFile
class Emogrifier
{
	/**
	 * @var int
	 */
	const CACHE_KEY_CSS = 0;

	/**
	 * @var int
	 */
	const CACHE_KEY_SELECTOR = 1;

	/**
	 * @var int
	 */
	const CACHE_KEY_XPATH = 2;

	/**
	 * @var int
	 */
	const CACHE_KEY_CSS_DECLARATIONS_BLOCK = 3;

	/**
	 * @var int
	 */
	const CACHE_KEY_COMBINED_STYLES = 4;

	/**
	 * for calculating nth-of-type and nth-child selectors
	 *
	 * @var int
	 */
	const INDEX = 0;

	/**
	 * for calculating nth-of-type and nth-child selectors
	 *
	 * @var int
	 */
	const MULTIPLIER = 1;

	/**
	 * @var string
	 */
	const ID_ATTRIBUTE_MATCHER = '/(\\w+)?\\#([\\w\\-]+)/';

	/**
	 * @var string
	 */
	const CLASS_ATTRIBUTE_MATCHER = '/(\\w+|[\\*\\]])?((\\.[\\w\\-]+)+)/';

	/**
	 * @var string
	 */
	const CONTENT_TYPE_META_TAG = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';

	/**
	 * @var string
	 */
	const DEFAULT_DOCUMENT_TYPE = '<!DOCTYPE html>';

	/**
	 * @var string
	 */
	private $html = '';

	/**
	 * @var string
	 */
	private $css = '';

	/**
	 * @var bool[]
	 */
	private $excludedSelectors = array();

	/**
	 * @var string[]
	 */
	private $unprocessableHtmlTags = array( 'wbr' );

	/**
	 * @var bool[]
	 */
	private $allowedMediaTypes = array( 'all' => true, 'screen' => true, 'print' => true );

	/**
<<<<<<< HEAD
	 * @var mixed[]
=======
	 * @var array[]
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	 */
	private $caches = array(
		self::CACHE_KEY_CSS => array(),
		self::CACHE_KEY_SELECTOR => array(),
		self::CACHE_KEY_XPATH => array(),
		self::CACHE_KEY_CSS_DECLARATIONS_BLOCK => array(),
		self::CACHE_KEY_COMBINED_STYLES => array(),
	);

	/**
	 * the visited nodes with the XPath paths as array keys
	 *
<<<<<<< HEAD
	 * @var \DOMElement[]
=======
	 * @var DoMElement[]
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	 */
	private $visitedNodes = array();

	/**
	 * the styles to apply to the nodes with the XPath paths as array keys for the outer array
	 * and the attribute names/values as key/value pairs for the inner array
	 *
<<<<<<< HEAD
	 * @var string[][]
=======
	 * @var array[]
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	 */
	private $styleAttributesForNodes = array();

	/**
	 * Determines whether the "style" attributes of tags in the the HTML passed to this class should be preserved.
	 * If set to false, the value of the style attributes will be discarded.
	 *
	 * @var bool
	 */
	private $isInlineStyleAttributesParsingEnabled = true;

	/**
	 * Determines whether the <style> blocks in the HTML passed to this class should be parsed.
	 *
	 * If set to true, the <style> blocks will be removed from the HTML and their contents will be applied to the HTML
	 * via inline styles.
	 *
	 * If set to false, the <style> blocks will be left as they are in the HTML.
	 *
	 * @var bool
	 */
	private $isStyleBlocksParsingEnabled = true;

	/**
	 * Determines whether elements with the `display: none` property are
	 * removed from the DOM.
	 *
	 * @var bool
	 */
	private $shouldKeepInvisibleNodes = true;

<<<<<<< HEAD
	/**
	 * @var string[]
	 */
	private $xPathRules = array(
		// child
		'/\\s*>\\s*/'                              => '/',
		// adjacent sibling
		'/\\s+\\+\\s+/'                            => '/following-sibling::*[1]/self::',
		// descendant
		'/\\s+(?=.*[^\\]]{1}$)/'                   => '//',
		// :first-child
		'/([^\\/]+):first-child/i'                 => '*[1]/self::\\1',
		// :last-child
		'/([^\\/]+):last-child/i'                  => '*[last()]/self::\\1',
		// attribute only
		'/^\\[(\\w+|\\w+\\=[\'"]?\\w+[\'"]?)\\]/'  => '*[@\\1]',
		// attribute
		'/(\\w)\\[(\\w+)\\]/'                      => '\\1[@\\2]',
		// exact attribute
		'/(\\w)\\[(\\w+)\\=[\'"]?([\\w\\s]+)[\'"]?\\]/' => '\\1[@\\2="\\3"]',
		// element attribute~=
		'/([\\w\\*]+)\\[(\\w+)[\\s]*\\~\\=[\\s]*[\'"]?([\\w-_\\/]+)[\'"]?\\]/'
			=> '\\1[contains(concat(" ", @\\2, " "), concat(" ", "\\3", " "))]',
		// element attribute^=
		'/([\\w\\*]+)\\[(\\w+)[\\s]*\\^\\=[\\s]*[\'"]?([\\w-_\\/]+)[\'"]?\\]/' => '\\1[starts-with(@\\2, "\\3")]',
		// element attribute*=
		'/([\\w\\*]+)\\[(\\w+)[\\s]*\\*\\=[\\s]*[\'"]?([\\w-_\\s\\/:;]+)[\'"]?\\]/' => '\\1[contains(@\\2, "\\3")]',
		// element attribute$=
		'/([\\w\\*]+)\\[(\\w+)[\\s]*\\$\\=[\\s]*[\'"]?([\\w-_\\s\\/]+)[\'"]?\\]/'
			=> '\\1[substring(@\\2, string-length(@\\2) - string-length("\\3") + 1) = "\\3"]',
		// element attribute|=
		'/([\\w\\*]+)\\[(\\w+)[\\s]*\\|\\=[\\s]*[\'"]?([\\w-_\\s\\/]+)[\'"]?\\]/'
			=> '\\1[@\\2="\\3" or starts-with(@\\2, concat("\\3", "-"))]',
	);

	/**
	 * Determines whether CSS styles that have an equivalent HTML attribute
	 * should be mapped and attached to those elements.
	 *
	 * @var bool
	 */
	private $shouldMapCssToHtml = false;

	/**
	 * This multi-level array contains simple mappings of CSS properties to
	 * HTML attributes. If a mapping only applies to certain HTML nodes or
	 * only for certain values, the mapping is an object with a whitelist
	 * of nodes and values.
	 *
	 * @var mixed[][]
	 */
	private $cssToHtmlMap = array(
		'background-color' => array(
			'attribute' => 'bgcolor',
		),
		'text-align' => array(
			'attribute' => 'align',
			'nodes' => array('p', 'div', 'td'),
			'values' => array('left', 'right', 'center', 'justify'),
		),
		'float' => array(
			'attribute' => 'align',
			'nodes' => array('table', 'img'),
			'values' => array('left', 'right'),
		),
		'border-spacing' => array(
			'attribute' => 'cellspacing',
			'nodes' => array('table'),
		),
	);

=======
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	public static $_media = '';

	/**
	 * The constructor.
	 *
	 * @param string $html the HTML to emogrify, must be UTF-8-encoded
	 * @param string $css the CSS to merge, must be UTF-8-encoded
	 */
<<<<<<< HEAD
	public function __construct($html = '', $css = '')
	{
=======
	public function __construct( $html = '', $css = '' ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		$this->setHtml($html);
		$this->setCss($css);
	}

	/**
	 * The destructor.
	 */
<<<<<<< HEAD
	public function __destruct()
	{
=======
	public function __destruct() {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		$this->purgeVisitedNodes();
	}

	/**
	 * Sets the HTML to emogrify.
	 *
	 * @param string $html the HTML to emogrify, must be UTF-8-encoded
	 *
	 * @return void
	 */
<<<<<<< HEAD
	public function setHtml($html)
	{
=======
	public function setHtml( $html ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		$this->html = $html;
	}

	/**
	 * Sets the CSS to merge with the HTML.
	 *
	 * @param string $css the CSS to merge, must be UTF-8-encoded
	 *
	 * @return void
	 */
<<<<<<< HEAD
	public function setCss($css)
	{
=======
	public function setCss( $css ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		$this->css = $css;
	}

	/**
	 * Applies $this->css to $this->html and returns the HTML with the CSS
	 * applied.
	 *
	 * This method places the CSS inline.
	 *
	 * @return string
	 *
<<<<<<< HEAD
	 * @throws \BadMethodCallException
	 */
	public function emogrify()
	{
		if ($this->html === '') {
			throw new BadMethodCallException('Please set some HTML first before calling emogrify.', 1390393096);
		}

		self::$_media = ''; // reset.

=======
	 * @throws BadMethodCallException
	 */
	public function emogrify() {
		if ( $this->html === '' ) {
			throw new BadMethodCallException('Please set some HTML first before calling emogrify.', 1390393096);
		}

		self::$_media = ''; // reset
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		$xmlDocument = $this->createXmlDocument();
		$this->process($xmlDocument);

		return $xmlDocument->saveHTML();
	}

	/**
	 * Applies $this->css to $this->html and returns only the HTML content
	 * within the <body> tag.
	 *
	 * This method places the CSS inline.
	 *
	 * @return string
	 *
<<<<<<< HEAD
	 * @throws \BadMethodCallException
	 */
	public function emogrifyBodyContent()
	{
		if ($this->html === '') {
=======
	 * @throws BadMethodCallException
	 */
	public function emogrifyBodyContent() {
		if ( $this->html === '' ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
			throw new BadMethodCallException('Please set some HTML first before calling emogrify.', 1390393096);
		}

		$xmlDocument = $this->createXmlDocument();
		$this->process($xmlDocument);

<<<<<<< HEAD
		$innerDocument = new DOMDocument();
		foreach ($xmlDocument->documentElement->getElementsByTagName('body')->item(0)->childNodes as $childNode) {
			$innerDocument->appendChild($innerDocument->importNode($childNode, true));
		}

		return html_entity_decode($innerDocument->saveHTML());
=======
		$innerDocument = new DoMDocument();
		foreach ( $xmlDocument->documentElement->getElementsByTagName('body')->item(0)->childNodes as $childNode ) {
			$innerDocument->appendChild($innerDocument->importNode($childNode, true));
		}

		return $innerDocument->saveHTML();
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	}

	/**
	 * Applies $this->css to $xmlDocument.
	 *
	 * This method places the CSS inline.
	 *
<<<<<<< HEAD
	 * @param \DOMDocument $xmlDocument
	 *
	 * @return void
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function process(DOMDocument $xmlDocument)
	{
		$xPath = new DOMXPath($xmlDocument);
=======
	 * @param DoMDocument $xmlDocument
	 *
	 * @return void
	 */
	protected function process( DoMDocument $xmlDocument ) {
		$xpath = new DoMXPath($xmlDocument);
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		$this->clearAllCaches();

		// Before be begin processing the CSS file, parse the document and normalize all existing CSS attributes.
		// This changes 'DISPLAY: none' to 'display: none'.
		// We wouldn't have to do this if DOMXPath supported XPath 2.0.
		// Also store a reference of nodes with existing inline styles so we don't overwrite them.
		$this->purgeVisitedNodes();

<<<<<<< HEAD
		set_error_handler(array($this, 'handleXpathError'), E_WARNING);

		$nodesWithStyleAttributes = $xPath->query('//*[@style]');
		if ($nodesWithStyleAttributes !== false) {
			/** @var \DOMElement $node */
			foreach ($nodesWithStyleAttributes as $node) {
				if ($this->isInlineStyleAttributesParsingEnabled) {
=======
		$nodesWithStyleAttributes = $xpath->query('//*[@style]');
		if ( $nodesWithStyleAttributes !== false ) {
			/** @var DoMElement $node */
			foreach ( $nodesWithStyleAttributes as $node ) {
				if ( $this->isInlineStyleAttributesParsingEnabled ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
					$this->normalizeStyleAttributes($node);
				} else {
					$node->removeAttribute('style');
				}
			}
		}

		// grab any existing style blocks from the html and append them to the existing CSS
		// (these blocks should be appended so as to have precedence over conflicting styles in the existing CSS)
		$allCss = $this->css;

<<<<<<< HEAD
		if ($this->isStyleBlocksParsingEnabled) {
			$allCss .= $this->getCssFromAllStyleNodes($xPath);
		}

		$cssParts = $this->splitCssAndMediaQuery($allCss);
		$excludedNodes = $this->getNodesToExclude($xPath);
		$cssRules = $this->parseCssRules($cssParts['css']);
		foreach ($cssRules as $cssRule) {
			// query the body for the xpath selector
			$nodesMatchingCssSelectors = $xPath->query($this->translateCssToXpath($cssRule['selector']));
			// ignore invalid selectors
			if ($nodesMatchingCssSelectors === false) {
				continue;
			}

			/** @var \DOMElement $node */
			foreach ($nodesMatchingCssSelectors as $node) {
				if (in_array($node, $excludedNodes, true)) {
=======
		if ( $this->isStyleBlocksParsingEnabled ) {
			$allCss .= $this->getCssFromAllStyleNodes($xpath);
		}

		$cssParts = $this->splitCssAndMediaQuery($allCss);
		$excludedNodes = $this->getNodesToExclude($xpath);
		$cssRules = $this->parseCssRules($cssParts['css']);
		foreach ( $cssRules as $cssRule ) {
			// query the body for the xpath selector
			$nodesMatchingCssSelectors = $xpath->query($this->translateCssToXpath($cssRule['selector']));
			// ignore invalid selectors
			if ( $nodesMatchingCssSelectors === false ) {
				continue;
			}

			/** @var DoMElement $node */
			foreach ( $nodesMatchingCssSelectors as $node ) {
				if ( in_array($node, $excludedNodes, true) ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
					continue;
				}

				// if it has a style attribute, get it, process it, and append (overwrite) new stuff
<<<<<<< HEAD
				if ($node->hasAttribute('style')) {
=======
				if ( $node->hasAttribute('style') ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
					// break it up into an associative array
					$oldStyleDeclarations = $this->parseCssDeclarationsBlock($node->getAttribute('style'));
				} else {
					$oldStyleDeclarations = array();
				}
				$newStyleDeclarations = $this->parseCssDeclarationsBlock($cssRule['declarationsBlock']);
<<<<<<< HEAD
				if ($this->shouldMapCssToHtml) {
					$this->mapCssToHtmlAttributes($newStyleDeclarations, $node);
				}
=======
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
				$node->setAttribute(
					'style',
					$this->generateStyleStringFromDeclarationsArrays($oldStyleDeclarations, $newStyleDeclarations)
				);
			}
		}

<<<<<<< HEAD
		restore_error_handler();

		if ($this->isInlineStyleAttributesParsingEnabled) {
			$this->fillStyleAttributesWithMergedStyles();
		}

		if ($this->shouldKeepInvisibleNodes) {
			$this->removeInvisibleNodes($xPath);
		}

		$this->copyCssWithMediaToStyleNode($xmlDocument, $xPath, $cssParts['media']);
	}

	/**
	 * Applies $styles to $node.
	 *
	 * This method maps CSS styles to HTML attributes and adds those to the
	 * node.
	 *
	 * @param string[] $styles the new CSS styles taken from the global styles to be applied to this node
	 * @param \DOMNode $node   node to apply styles to
	 *
	 * @return void
	 */
	private function mapCssToHtmlAttributes(array $styles, DOMNode $node)
	{
		foreach ($styles as $property => $value) {
			// Strip !important indicator
			$value = trim(str_replace('!important', '', $value));
			$this->mapCssToHtmlAttribute($property, $value, $node);
		}
	}

	/**
	 * Tries to apply the CSS style to $node as an attribute.
	 *
	 * This method maps a CSS rule to HTML attributes and adds those to the node.
	 *
	 * @param string $property the name of the CSS property to map
	 * @param string $value    the value of the style rule to map
	 * @param \DOMNode $node   node to apply styles to
	 *
	 * @return void
	 */
	private function mapCssToHtmlAttribute($property, $value, DOMNode $node)
	{
		if (!$this->mapSimpleCssProperty($property, $value, $node)) {
			$this->mapComplexCssProperty($property, $value, $node);
		}
	}

	/**
	 * Looks up the CSS property in the mapping table and maps it if it matches the conditions.
	 *
	 * @param string $property the name of the CSS property to map
	 * @param string $value    the value of the style rule to map
	 * @param \DOMNode $node   node to apply styles to
	 *
	 * @return bool true if the property cab be mapped using the simple mapping table
	 */
	private function mapSimpleCssProperty($property, $value, DOMNode $node)
	{
		if (!isset($this->cssToHtmlMap[$property])) {
			return false;
		}

		$mapping = $this->cssToHtmlMap[$property];
		$nodesMatch = !isset($mapping['nodes']) || in_array($node->nodeName, $mapping['nodes'], true);
		$valuesMatch = !isset($mapping['values']) || in_array($value, $mapping['values'], true);
		if (!$nodesMatch || !$valuesMatch) {
			return false;
		}

		$node->setAttribute($mapping['attribute'], $value);

		return true;
	}

	/**
	 * Maps CSS properties that need special transformation to an HTML attribute.
	 *
	 * @param string $property the name of the CSS property to map
	 * @param string $value    the value of the style rule to map
	 * @param \DOMNode $node   node to apply styles to
	 *
	 * @return void
	 */
	private function mapComplexCssProperty($property, $value, DOMNode $node)
	{
		$nodeName = $node->nodeName;
		$isTable = $nodeName === 'table';
		$isImage = $nodeName === 'img';
		$isTableOrImage = $isTable || $isImage;

		switch ($property) {
			case 'background':
				// Parse out the color, if any
				$styles = explode(' ', $value);
				$first = $styles[0];
				if (!is_numeric(substr($first, 0, 1)) && substr($first, 0, 3) !== 'url') {
					// This is not a position or image, assume it's a color
					$node->setAttribute('bgcolor', $first);
				}
				break;
			case 'width':
				// intentional fall-through
			case 'height':
				// Only parse values in px and %, but not values like "auto".
				if (preg_match('/^\d+(px|%)$/', $value)) {
					// Remove 'px'. This regex only conserves numbers and %
					$number = preg_replace('/[^0-9.%]/', '', $value);
					$node->setAttribute($property, $number);
				}
				break;
			case 'margin':
				if ($isTableOrImage) {
					$margins = $this->parseCssShorthandValue($value);
					if ($margins['left'] === 'auto' && $margins['right'] === 'auto') {
						$node->setAttribute('align', 'center');
					}
				}
				break;
			case 'border':
				if ($isTableOrImage) {
					if ($value === 'none' || $value === '0') {
						$node->setAttribute('border', '0');
					}
				}
				break;
			default:
		}
	}

	/**
	 * Parses a shorthand CSS value and splits it into individual values
	 *
	 * @param string $value a string of CSS value with 1, 2, 3 or 4 sizes
	 *                      For example: padding: 0 auto;
	 *                      '0 auto' is split into top: 0, left: auto, bottom: 0,
	 *                      right: auto.
	 *
	 * @return string[] an array of values for top, right, bottom and left (using these as associative array keys)
	 */
	private function parseCssShorthandValue($value)
	{
		$values = preg_split('/\\s+/', $value);

		$css = array();
		$css['top'] = $values[0];
		$css['right'] = (count($values) > 1) ? $values[1] : $css['top'];
		$css['bottom'] = (count($values) > 2) ? $values[2] : $css['top'];
		$css['left'] = (count($values) > 3) ? $values[3] : $css['right'];

		return $css;
=======
		if ( $this->isInlineStyleAttributesParsingEnabled ) {
			$this->fillStyleAttributesWithMergedStyles();
		}

		if ( $this->shouldKeepInvisibleNodes ) {
			$this->removeInvisibleNodes($xpath);
		}

		$this->copyCssWithMediaToStyleNode($xmlDocument, $xpath, $cssParts['media']);
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	}

	/**
	 * Extracts and parses the individual rules from a CSS string.
	 *
	 * @param string $css a string of raw CSS code
	 *
	 * @return string[][] an array of string sub-arrays with the keys
	 *         "selector" (the CSS selector(s), e.g., "*" or "h1"),
	 *         "declarationsBLock" (the semicolon-separated CSS declarations for that selector(s),
	 *         e.g., "color: red; height: 4px;"),
	 *         and "line" (the line number e.g. 42)
	 */
<<<<<<< HEAD
	private function parseCssRules($css)
	{
		$cssKey = md5($css);
		if (!isset($this->caches[self::CACHE_KEY_CSS][$cssKey])) {
=======
	private function parseCssRules( $css ) {
		$cssKey = md5($css);
		if ( ! isset($this->caches[ self::CACHE_KEY_CSS ][ $cssKey ]) ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
			// process the CSS file for selectors and definitions
			preg_match_all('/(?:^|[\\s^{}]*)([^{]+){([^}]*)}/mis', $css, $matches, PREG_SET_ORDER);

			$cssRules = array();
			/** @var string[] $cssRule */
<<<<<<< HEAD
			foreach ($matches as $key => $cssRule) {
				$cssDeclaration = trim($cssRule[2]);
				if ($cssDeclaration === '') {
=======
			foreach ( $matches as $key => $cssRule ) {
				$cssDeclaration = trim($cssRule[2]);
				if ( $cssDeclaration === '' ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
					continue;
				}

				$selectors = explode(',', $cssRule[1]);
<<<<<<< HEAD
				foreach ($selectors as $selector) {
					// don't process pseudo-elements and behavioral (dynamic) pseudo-classes;
					// only allow structural pseudo-classes
					$hasPseudoElement = strpos($selector, '::') !== false;
					$hasAnyPseudoClass = (bool) preg_match('/:[a-zA-Z]/', $selector);
					$hasSupportedPseudoClass = (bool) preg_match('/:\\S+\\-(child|type\\()/i', $selector);
					if ($hasPseudoElement || ($hasAnyPseudoClass && !$hasSupportedPseudoClass)) {
=======
				foreach ( $selectors as $selector ) {
					// don't process pseudo-elements and behavioral (dynamic) pseudo-classes;
					// only allow structural pseudo-classes
					if ( strpos($selector, ':') !== false && ! preg_match('/:\\S+\\-(child|type\\()/i', $selector) ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
						continue;
					}

					$cssRules[] = array(
						'selector' => trim($selector),
						'declarationsBlock' => $cssDeclaration,
						// keep track of where it appears in the file, since order is important
						'line' => $key,
					);
				}
			}

<<<<<<< HEAD
			usort($cssRules, array($this, 'sortBySelectorPrecedence'));

			$this->caches[self::CACHE_KEY_CSS][$cssKey] = $cssRules;
		}

		return $this->caches[self::CACHE_KEY_CSS][$cssKey];
=======
			usort($cssRules, array( $this, 'sortBySelectorPrecedence' ) );

			$this->caches[ self::CACHE_KEY_CSS ][ $cssKey ] = $cssRules;
		}

		return $this->caches[ self::CACHE_KEY_CSS ][ $cssKey ];
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	}

	/**
	 * Disables the parsing of inline styles.
	 *
	 * @return void
	 */
<<<<<<< HEAD
	public function disableInlineStyleAttributesParsing()
	{
=======
	public function disableInlineStyleAttributesParsing() {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		$this->isInlineStyleAttributesParsingEnabled = false;
	}

	/**
	 * Disables the parsing of <style> blocks.
	 *
	 * @return void
	 */
<<<<<<< HEAD
	public function disableStyleBlocksParsing()
	{
=======
	public function disableStyleBlocksParsing() {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		$this->isStyleBlocksParsingEnabled = false;
	}

	/**
	 * Disables the removal of elements with `display: none` properties.
	 *
	 * @return void
	 */
<<<<<<< HEAD
	public function disableInvisibleNodeRemoval()
	{
=======
	public function disableInvisibleNodeRemoval() {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		$this->shouldKeepInvisibleNodes = false;
	}

	/**
<<<<<<< HEAD
	 * Enables the attachment/override of HTML attributes for which a
	 * corresponding CSS property has been set.
	 *
	 * @return void
	 */
	public function enableCssToHtmlMapping()
	{
		$this->shouldMapCssToHtml = true;
	}

	/**
=======
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	 * Clears all caches.
	 *
	 * @return void
	 */
<<<<<<< HEAD
	private function clearAllCaches()
	{
=======
	private function clearAllCaches() {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		$this->clearCache(self::CACHE_KEY_CSS);
		$this->clearCache(self::CACHE_KEY_SELECTOR);
		$this->clearCache(self::CACHE_KEY_XPATH);
		$this->clearCache(self::CACHE_KEY_CSS_DECLARATIONS_BLOCK);
		$this->clearCache(self::CACHE_KEY_COMBINED_STYLES);
	}

	/**
	 * Clears a single cache by key.
	 *
	 * @param int $key the cache key, must be CACHE_KEY_CSS, CACHE_KEY_SELECTOR, CACHE_KEY_XPATH
	 *                 or CACHE_KEY_CSS_DECLARATION_BLOCK
	 *
	 * @return void
	 *
	 * @throws \InvalidArgumentException
	 */
<<<<<<< HEAD
	private function clearCache($key)
	{
=======
	private function clearCache( $key ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		$allowedCacheKeys = array(
			self::CACHE_KEY_CSS,
			self::CACHE_KEY_SELECTOR,
			self::CACHE_KEY_XPATH,
			self::CACHE_KEY_CSS_DECLARATIONS_BLOCK,
			self::CACHE_KEY_COMBINED_STYLES,
		);
<<<<<<< HEAD
		if (!in_array($key, $allowedCacheKeys, true)) {
			throw new InvalidArgumentException('Invalid cache key: ' . $key, 1391822035);
		}

		$this->caches[$key] = array();
=======
		if ( ! in_array($key, $allowedCacheKeys, true) ) {
			throw new InvalidArgumentException('Invalid cache key: ' . $key, 1391822035);
		}

		$this->caches[ $key ] = array();
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	}

	/**
	 * Purges the visited nodes.
	 *
	 * @return void
	 */
<<<<<<< HEAD
	private function purgeVisitedNodes()
	{
=======
	private function purgeVisitedNodes() {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		$this->visitedNodes = array();
		$this->styleAttributesForNodes = array();
	}

	/**
	 * Marks a tag for removal.
	 *
	 * There are some HTML tags that DOMDocument cannot process, and it will throw an error if it encounters them.
	 * In particular, DOMDocument will complain if you try to use HTML5 tags in an XHTML document.
	 *
	 * Note: The tags will not be removed if they have any content.
	 *
	 * @param string $tagName the tag name, e.g., "p"
	 *
	 * @return void
	 */
<<<<<<< HEAD
	public function addUnprocessableHtmlTag($tagName)
	{
=======
	public function addUnprocessableHtmlTag( $tagName ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		$this->unprocessableHtmlTags[] = $tagName;
	}

	/**
	 * Drops a tag from the removal list.
	 *
	 * @param string $tagName the tag name, e.g., "p"
	 *
	 * @return void
	 */
<<<<<<< HEAD
	public function removeUnprocessableHtmlTag($tagName)
	{
		$key = array_search($tagName, $this->unprocessableHtmlTags, true);
		if ($key !== false) {
			unset($this->unprocessableHtmlTags[$key]);
=======
	public function removeUnprocessableHtmlTag( $tagName ) {
		$key = array_search($tagName, $this->unprocessableHtmlTags, true);
		if ( $key !== false ) {
			unset($this->unprocessableHtmlTags[ $key ]);
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		}
	}

	/**
	 * Marks a media query type to keep.
	 *
	 * @param string $mediaName the media type name, e.g., "braille"
	 *
	 * @return void
	 */
<<<<<<< HEAD
	public function addAllowedMediaType($mediaName)
	{
		$this->allowedMediaTypes[$mediaName] = true;
=======
	public function addAllowedMediaType( $mediaName ) {
		$this->allowedMediaTypes[ $mediaName ] = true;
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	}

	/**
	 * Drops a media query type from the allowed list.
	 *
	 * @param string $mediaName the tag name, e.g., "braille"
	 *
	 * @return void
	 */
<<<<<<< HEAD
	public function removeAllowedMediaType($mediaName)
	{
		if (isset($this->allowedMediaTypes[$mediaName])) {
			unset($this->allowedMediaTypes[$mediaName]);
=======
	public function removeAllowedMediaType( $mediaName ) {
		if ( isset($this->allowedMediaTypes[ $mediaName ]) ) {
			unset($this->allowedMediaTypes[ $mediaName ]);
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		}
	}

	/**
	 * Adds a selector to exclude nodes from emogrification.
	 *
	 * Any nodes that match the selector will not have their style altered.
	 *
	 * @param string $selector the selector to exclude, e.g., ".editor"
	 *
	 * @return void
	 */
<<<<<<< HEAD
	public function addExcludedSelector($selector)
	{
		$this->excludedSelectors[$selector] = true;
=======
	public function addExcludedSelector( $selector ) {
		$this->excludedSelectors[ $selector ] = true;
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	}

	/**
	 * No longer excludes the nodes matching this selector from emogrification.
	 *
	 * @param string $selector the selector to no longer exclude, e.g., ".editor"
	 *
	 * @return void
	 */
<<<<<<< HEAD
	public function removeExcludedSelector($selector)
	{
		if (isset($this->excludedSelectors[$selector])) {
			unset($this->excludedSelectors[$selector]);
=======
	public function removeExcludedSelector( $selector ) {
		if ( isset($this->excludedSelectors[ $selector ]) ) {
			unset($this->excludedSelectors[ $selector ]);
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		}
	}

	/**
	 * This removes styles from your email that contain display:none.
	 * We need to look for display:none, but we need to do a case-insensitive search. Since DOMDocument only
	 * supports XPath 1.0, lower-case() isn't available to us. We've thus far only set attributes to lowercase,
	 * not attribute values. Consequently, we need to translate() the letters that would be in 'NONE' ("NOE")
	 * to lowercase.
	 *
<<<<<<< HEAD
	 * @param \DOMXPath $xPath
	 *
	 * @return void
	 */
	private function removeInvisibleNodes(DOMXPath $xPath)
	{
		$nodesWithStyleDisplayNone = $xPath->query(
			'//*[contains(translate(translate(@style," ",""),"NOE","noe"),"display:none")]'
		);
		if ($nodesWithStyleDisplayNone->length === 0) {
=======
	 * @param DoMXPath $xpath
	 *
	 * @return void
	 */
	private function removeInvisibleNodes( DoMXPath $xpath ) {
		$nodesWithStyleDisplayNone = $xpath->query(
			'//*[contains(translate(translate(@style," ",""),"NOE","noe"),"display:none")]'
		);
		if ( $nodesWithStyleDisplayNone->length === 0 ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
			return;
		}

		// The checks on parentNode and is_callable below ensure that if we've deleted the parent node,
		// we don't try to call removeChild on a nonexistent child node
<<<<<<< HEAD
		/** @var \DOMNode $node */
		foreach ($nodesWithStyleDisplayNone as $node) {
			if ($node->parentNode && is_callable(array($node->parentNode, 'removeChild'))) {
=======
		/** @var DoMNode $node */
		foreach ( $nodesWithStyleDisplayNone as $node ) {
			if ( $node->parentNode && is_callable( array( $node->parentNode, 'removeChild' ) ) ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
				$node->parentNode->removeChild($node);
			}
		}
	}

	private function normalizeStyleAttributes_callback( $m ) {
		return strtolower( $m[0] );
	}

	/**
	 * Normalizes the value of the "style" attribute and saves it.
	 *
<<<<<<< HEAD
	 * @param \DOMElement $node
	 *
	 * @return void
	 */
	private function normalizeStyleAttributes(DOMElement $node)
	{
=======
	 * @param DoMElement $node
	 *
	 * @return void
	 */
	private function normalizeStyleAttributes( DoMElement $node ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		$normalizedOriginalStyle = preg_replace_callback(
			'/[A-z\\-]+(?=\\:)/S',
			array( $this, 'normalizeStyleAttributes_callback' ),
			$node->getAttribute('style')
		);

		// in order to not overwrite existing style attributes in the HTML, we
		// have to save the original HTML styles
		$nodePath = $node->getNodePath();
<<<<<<< HEAD
		if (!isset($this->styleAttributesForNodes[$nodePath])) {
			$this->styleAttributesForNodes[$nodePath] = $this->parseCssDeclarationsBlock($normalizedOriginalStyle);
			$this->visitedNodes[$nodePath] = $node;
=======
		if ( ! isset($this->styleAttributesForNodes[ $nodePath ]) ) {
			$this->styleAttributesForNodes[ $nodePath ] = $this->parseCssDeclarationsBlock($normalizedOriginalStyle);
			$this->visitedNodes[ $nodePath ] = $node;
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		}

		$node->setAttribute('style', $normalizedOriginalStyle);
	}

	/**
	 * Merges styles from styles attributes and style nodes and applies them to the attribute nodes
	 *
	 * @return void
	 */
<<<<<<< HEAD
	private function fillStyleAttributesWithMergedStyles()
	{
		foreach ($this->styleAttributesForNodes as $nodePath => $styleAttributesForNode) {
			$node = $this->visitedNodes[$nodePath];
=======
	private function fillStyleAttributesWithMergedStyles() {
		foreach ( $this->styleAttributesForNodes as $nodePath => $styleAttributesForNode ) {
			$node = $this->visitedNodes[ $nodePath ];
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
			$currentStyleAttributes = $this->parseCssDeclarationsBlock($node->getAttribute('style'));
			$node->setAttribute(
				'style',
				$this->generateStyleStringFromDeclarationsArrays(
					$currentStyleAttributes,
					$styleAttributesForNode
				)
			);
		}
	}

	/**
	 * This method merges old or existing name/value array with new name/value array
	 * and then generates a string of the combined style suitable for placing inline.
	 * This becomes the single point for CSS string generation allowing for consistent
	 * CSS output no matter where the CSS originally came from.
	 *
	 * @param string[] $oldStyles
	 * @param string[] $newStyles
	 *
	 * @return string
	 */
<<<<<<< HEAD
	private function generateStyleStringFromDeclarationsArrays(array $oldStyles, array $newStyles)
	{
		$combinedStyles = array_merge($oldStyles, $newStyles);
		$cacheKey = serialize($combinedStyles);
		if (isset($this->caches[self::CACHE_KEY_COMBINED_STYLES][$cacheKey])) {
			return $this->caches[self::CACHE_KEY_COMBINED_STYLES][$cacheKey];
		}

		foreach ($oldStyles as $attributeName => $attributeValue) {
			if (!isset($newStyles[$attributeName])) {
				continue;
			}

			$newAttributeValue = $newStyles[$attributeName];
			if ($this->attributeValueIsImportant($attributeValue)
				&& !$this->attributeValueIsImportant($newAttributeValue)
			) {
				$combinedStyles[$attributeName] = $attributeValue;
=======
	private function generateStyleStringFromDeclarationsArrays( array $oldStyles, array $newStyles ) {
		$combinedStyles = array_merge($oldStyles, $newStyles);
		$cacheKey = serialize( $combinedStyles );
		if ( isset($this->caches[ self::CACHE_KEY_COMBINED_STYLES ][ $cacheKey ]) ) {
			return $this->caches[ self::CACHE_KEY_COMBINED_STYLES ][ $cacheKey ];
		}

		foreach ( $oldStyles as $attributeName => $attributeValue ) {
			if ( isset($newStyles[ $attributeName ]) && strtolower(substr($attributeValue, -10)) === '!important' ) {
				$combinedStyles[ $attributeName ] = $attributeValue;
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
			}
		}

		$style = '';
<<<<<<< HEAD
		foreach ($combinedStyles as $attributeName => $attributeValue) {
=======
		foreach ( $combinedStyles as $attributeName => $attributeValue ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
			$style .= strtolower(trim($attributeName)) . ': ' . trim($attributeValue) . '; ';
		}
		$trimmedStyle = rtrim($style);

<<<<<<< HEAD
		$this->caches[self::CACHE_KEY_COMBINED_STYLES][$cacheKey] = $trimmedStyle;
=======
		$this->caches[ self::CACHE_KEY_COMBINED_STYLES ][ $cacheKey ] = $trimmedStyle;
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed

		return $trimmedStyle;
	}

	/**
<<<<<<< HEAD
	 * Checks whether $attributeValue is marked as !important.
	 *
	 * @param string $attributeValue
	 *
	 * @return bool
	 */
	private function attributeValueIsImportant($attributeValue)
	{
		return strtolower(substr(trim($attributeValue), -10)) === '!important';
	}

	/**
	 * Applies $css to $xmlDocument, limited to the media queries that actually apply to the document.
	 *
	 * @param \DOMDocument $xmlDocument the document to match against
	 * @param \DOMXPath $xPath
=======
	 * Applies $css to $xmlDocument, limited to the media queries that actually apply to the document.
	 *
	 * @param DoMDocument $xmlDocument the document to match against
	 * @param DoMXPath $xpath
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	 * @param string $css a string of CSS
	 *
	 * @return void
	 */
<<<<<<< HEAD
	private function copyCssWithMediaToStyleNode(DOMDocument $xmlDocument, DOMXPath $xPath, $css)
	{
		if ($css === '') {
=======
	private function copyCssWithMediaToStyleNode( DoMDocument $xmlDocument, DoMXPath $xpath, $css ) {
		if ( $css === '' ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
			return;
		}

		$mediaQueriesRelevantForDocument = array();

<<<<<<< HEAD
		foreach ($this->extractMediaQueriesFromCss($css) as $mediaQuery) {
			foreach ($this->parseCssRules($mediaQuery['css']) as $selector) {
				if ($this->existsMatchForCssSelector($xPath, $selector['selector'])) {
=======
		foreach ( $this->extractMediaQueriesFromCss($css) as $mediaQuery ) {
			foreach ( $this->parseCssRules($mediaQuery['css']) as $selector ) {
				if ( $this->existsMatchForCssSelector($xpath, $selector['selector']) ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
					$mediaQueriesRelevantForDocument[] = $mediaQuery['query'];
					break;
				}
			}
		}

		$this->addStyleElementToDocument($xmlDocument, implode($mediaQueriesRelevantForDocument));
	}

	/**
<<<<<<< HEAD
	 * Extracts the media queries from $css while skipping empty media queries.
=======
	 * Extracts the media queries from $css.
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	 *
	 * @param string $css
	 *
	 * @return string[][] numeric array with string sub-arrays with the keys "css" and "query"
	 */
<<<<<<< HEAD
	private function extractMediaQueriesFromCss($css)
	{
		preg_match_all('/@media\\b[^{]*({((?:[^{}]+|(?1))*)})/', $css, $rawMediaQueries, PREG_SET_ORDER);
		$parsedQueries = array();

		foreach ($rawMediaQueries as $mediaQuery) {
			if ($mediaQuery[2] !== '') {
				$parsedQueries[] = array(
					'css'   => $mediaQuery[2],
					'query' => $mediaQuery[0],
				);
			}
		}

		return $parsedQueries;
=======
	private function extractMediaQueriesFromCss( $css ) {
		preg_match_all('#(?<query>@media[^{]*\\{(?<css>(.*?)\\})(\\s*)\\})#s', $css, $mediaQueries);
		$result = array();
		foreach ( array_keys($mediaQueries['css']) as $key ) {
			$result[] = array(
				'css' => $mediaQueries['css'][ $key ],
				'query' => $mediaQueries['query'][ $key ],
			);
		}
		return $result;
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	}

	/**
	 * Checks whether there is at least one matching element for $cssSelector.
	 *
<<<<<<< HEAD
	 * @param \DOMXPath $xPath
=======
	 * @param DoMXPath $xpath
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	 * @param string $cssSelector
	 *
	 * @return bool
	 */
<<<<<<< HEAD
	private function existsMatchForCssSelector(DOMXPath $xPath, $cssSelector)
	{
		$nodesMatchingSelector = $xPath->query($this->translateCssToXpath($cssSelector));
=======
	private function existsMatchForCssSelector( DoMXPath $xpath, $cssSelector ) {
		$nodesMatchingSelector = $xpath->query($this->translateCssToXpath($cssSelector));
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed

		return $nodesMatchingSelector !== false && $nodesMatchingSelector->length !== 0;
	}

	/**
	 * Returns CSS content.
	 *
<<<<<<< HEAD
	 * @param \DOMXPath $xPath
	 *
	 * @return string
	 */
	private function getCssFromAllStyleNodes(DOMXPath $xPath)
	{
		$styleNodes = $xPath->query('//style');

		if ($styleNodes === false) {
=======
	 * @param DoMXPath $xpath
	 *
	 * @return string
	 */
	private function getCssFromAllStyleNodes( DoMXPath $xpath ) {
		$styleNodes = $xpath->query('//style');

		if ( $styleNodes === false ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
			return '';
		}

		$css = '';
<<<<<<< HEAD
		/** @var \DOMNode $styleNode */
		foreach ($styleNodes as $styleNode) {
=======
		/** @var DoMNode $styleNode */
		foreach ( $styleNodes as $styleNode ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
			$css .= "\n\n" . $styleNode->nodeValue;
			$styleNode->parentNode->removeChild($styleNode);
		}

		return $css;
	}

	/**
	 * Adds a style element with $css to $document.
	 *
	 * This method is protected to allow overriding.
	 *
	 * @see https://github.com/jjriv/emogrifier/issues/103
	 *
<<<<<<< HEAD
	 * @param \DOMDocument $document
=======
	 * @param DoMDocument $document
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	 * @param string $css
	 *
	 * @return void
	 */
<<<<<<< HEAD
	protected function addStyleElementToDocument(DOMDocument $document, $css)
	{
=======
	protected function addStyleElementToDocument( DoMDocument $document, $css ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		$styleElement = $document->createElement('style', $css);
		$styleAttribute = $document->createAttribute('type');
		$styleAttribute->value = 'text/css';
		$styleElement->appendChild($styleAttribute);

		$head = $this->getOrCreateHeadElement($document);
		$head->appendChild($styleElement);
	}

	/**
	 * Returns the existing or creates a new head element in $document.
	 *
<<<<<<< HEAD
	 * @param \DOMDocument $document
	 *
	 * @return \DOMNode the head element
	 */
	private function getOrCreateHeadElement(DOMDocument $document)
	{
		$head = $document->getElementsByTagName('head')->item(0);

		if ($head === null) {
=======
	 * @param DoMDocument $document
	 *
	 * @return DoMNode the head element
	 */
	private function getOrCreateHeadElement( DoMDocument $document ) {
		$head = $document->getElementsByTagName('head')->item(0);

		if ( $head === null ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
			$head = $document->createElement('head');
			$html = $document->getElementsByTagName('html')->item(0);
			$html->insertBefore($head, $document->getElementsByTagName('body')->item(0));
		}

		return $head;
	}

<<<<<<< HEAD
	/**
	 * Splits input CSS code to an array where:
	 *
	 * - key "css" will be contains clean CSS code
	 * - key "media" will be contains all valuable media queries
	 *
	 * Example:
	 *
	 * The CSS code
=======
	private function splitCssAndMediaQuery_callback() {

	}

	/**
	 * Splits input CSS code to an array where:
	 *
	 * - key "css" will be contains clean CSS code.
	 * - key "media" will be contains all valuable media queries.
	 *
	 * Example:
	 *
	 * The CSS code.
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	 *
	 *   "@import "file.css"; h1 { color:red; } @media { h1 {}} @media tv { h1 {}}"
	 *
	 * will be parsed into the following array:
	 *
	 *   "css" => "h1 { color:red; }"
	 *   "media" => "@media { h1 {}}"
	 *
	 * @param string $css
<<<<<<< HEAD
	 *
	 * @return string[]
	 */
	private function splitCssAndMediaQuery($css)
	{
		$cssWithoutComments = preg_replace('/\\/\\*.*\\*\\//sU', '', $css);

		$mediaTypesExpression = '';
		if (!empty($this->allowedMediaTypes)) {
			$mediaTypesExpression = '|' . implode('|', array_keys($this->allowedMediaTypes));
		}

		$cssForAllowedMediaTypes = preg_replace_callback(
			'#@media\\s+(?:only\\s)?(?:[\\s{\\(]' . $mediaTypesExpression . ')\\s?[^{]+{.*}\\s*}\\s*#misU',
			array( $this, '_media_concat' ),
			$cssWithoutComments
		);

		// filter the CSS
		$search = array(
			'import directives' => '/^\\s*@import\\s[^;]+;/misU',
			'remaining media enclosures' => '/^\\s*@media\\s[^{]+{(.*)}\\s*}\\s/misU',
		);

		$cleanedCss = preg_replace($search, '', $cssForAllowedMediaTypes);

		return array('css' => $cleanedCss, 'media' => self::$_media);
=======
	 * @return array
	 */
	private function splitCssAndMediaQuery( $css ) {
		$css = preg_replace_callback( '#@media\\s+(?:only\\s)?(?:[\\s{\(]|screen|all)\\s?[^{]+{.*}\\s*}\\s*#misU', array( $this, '_media_concat' ), $css );
		// filter the CSS
		$search = array(
			// get rid of css comment code
			'/\\/\\*.*\\*\\//sU',
			// strip out any import directives
			'/^\\s*@import\\s[^;]+;/misU',
			// strip remains media enclosures
			'/^\\s*@media\\s[^{]+{(.*)}\\s*}\\s/misU',
		);
		$replace = array(
			'',
			'',
			'',
		);
		// clean CSS before output
		$css = preg_replace($search, $replace, $css);
		return array( 'css' => $css, 'media' => self::$_media );
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	}

	private function _media_concat( $matches ) {
		self::$_media .= $matches[0];
	}

	/**
	 * Creates a DOMDocument instance with the current HTML.
	 *
<<<<<<< HEAD
	 * @return \DOMDocument
	 */
	private function createXmlDocument()
	{
		$xmlDocument = new DOMDocument;
=======
	 * @return DoMDocument
	 */
	private function createXmlDocument() {
		$xmlDocument = new DoMDocument;
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		$xmlDocument->encoding = 'UTF-8';
		$xmlDocument->strictErrorChecking = false;
		$xmlDocument->formatOutput = true;
		$libXmlState = libxml_use_internal_errors(true);
		$xmlDocument->loadHTML($this->getUnifiedHtml());
		libxml_clear_errors();
		libxml_use_internal_errors($libXmlState);
		$xmlDocument->normalizeDocument();

		return $xmlDocument;
	}

	/**
	 * Returns the HTML with the unprocessable HTML tags removed and
	 * with added document type and Content-Type meta tag if needed.
	 *
	 * @return string the unified HTML
	 *
<<<<<<< HEAD
	 * @throws \BadMethodCallException
	 */
	private function getUnifiedHtml()
	{
=======
	 * @throws BadMethodCallException
	 */
	private function getUnifiedHtml() {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		$htmlWithoutUnprocessableTags = $this->removeUnprocessableTags($this->html);
		$htmlWithDocumentType = $this->ensureDocumentType($htmlWithoutUnprocessableTags);

		return $this->addContentTypeMetaTag($htmlWithDocumentType);
	}

	/**
	 * Removes the unprocessable tags from $html (if this feature is enabled).
	 *
	 * @param string $html
	 *
	 * @return string the reworked HTML with the unprocessable tags removed
	 */
<<<<<<< HEAD
	private function removeUnprocessableTags($html)
	{
		if (empty($this->unprocessableHtmlTags)) {
=======
	private function removeUnprocessableTags( $html ) {
		if ( empty($this->unprocessableHtmlTags) ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
			return $html;
		}

		$unprocessableHtmlTags = implode('|', $this->unprocessableHtmlTags);

		return preg_replace(
			'/<\\/?(' . $unprocessableHtmlTags . ')[^>]*>/i',
			'',
			$html
		);
	}

	/**
	 * Makes sure that the passed HTML has a document type.
	 *
	 * @param string $html
	 *
	 * @return string HTML with document type
	 */
<<<<<<< HEAD
	private function ensureDocumentType($html)
	{
		$hasDocumentType = stripos($html, '<!DOCTYPE') !== false;
		if ($hasDocumentType) {
=======
	private function ensureDocumentType( $html ) {
		$hasDocumentType = stripos($html, '<!DOCTYPE') !== false;
		if ( $hasDocumentType ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
			return $html;
		}

		return self::DEFAULT_DOCUMENT_TYPE . $html;
	}

	/**
	 * Adds a Content-Type meta tag for the charset.
	 *
	 * @param string $html
	 *
	 * @return string the HTML with the meta tag added
	 */
<<<<<<< HEAD
	private function addContentTypeMetaTag($html)
	{
		$hasContentTypeMetaTag = stristr($html, 'Content-Type') !== false;
		if ($hasContentTypeMetaTag) {
			return $html;
=======
	private function addContentTypeMetaTag( $html ) {
		$hasContentTypeMetaTag = stristr($html, 'Content-Type') !== false;
		if ( $hasContentTypeMetaTag ) {
			return $html;

>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		}

		// We are trying to insert the meta tag to the right spot in the DOM.
		// If we just prepended it to the HTML, we would lose attributes set to the HTML tag.
		$hasHeadTag = stripos($html, '<head') !== false;
		$hasHtmlTag = stripos($html, '<html') !== false;

<<<<<<< HEAD
		if ($hasHeadTag) {
			$reworkedHtml = preg_replace('/<head(.*?)>/i', '<head$1>' . self::CONTENT_TYPE_META_TAG, $html);
		} elseif ($hasHtmlTag) {
=======
		if ( $hasHeadTag ) {
			$reworkedHtml = preg_replace('/<head(.*?)>/i', '<head$1>' . self::CONTENT_TYPE_META_TAG, $html);
		} elseif ( $hasHtmlTag ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
			$reworkedHtml = preg_replace(
				'/<html(.*?)>/i',
				'<html$1><head>' . self::CONTENT_TYPE_META_TAG . '</head>',
				$html
			);
		} else {
			$reworkedHtml = self::CONTENT_TYPE_META_TAG . $html;
		}

		return $reworkedHtml;
	}

	/**
	 * @param string[] $a
	 * @param string[] $b
	 *
	 * @return int
	 */
<<<<<<< HEAD
	private function sortBySelectorPrecedence(array $a, array $b)
	{
=======
	private function sortBySelectorPrecedence( array $a, array $b ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		$precedenceA = $this->getCssSelectorPrecedence($a['selector']);
		$precedenceB = $this->getCssSelectorPrecedence($b['selector']);

		// We want these sorted in ascending order so selectors with lesser precedence get processed first and
		// selectors with greater precedence get sorted last.
		$precedenceForEquals = ($a['line'] < $b['line'] ? -1 : 1);
		$precedenceForNotEquals = ($precedenceA < $precedenceB ? -1 : 1);
		return ($precedenceA === $precedenceB) ? $precedenceForEquals : $precedenceForNotEquals;
	}

	/**
	 * @param string $selector
	 *
	 * @return int
	 */
<<<<<<< HEAD
	private function getCssSelectorPrecedence($selector)
	{
		$selectorKey = md5($selector);
		if (!isset($this->caches[self::CACHE_KEY_SELECTOR][$selectorKey])) {
			$precedence = 0;
			$value = 100;
			// ids: worth 100, classes: worth 10, elements: worth 1
			$search = array('\\#','\\.','');

			foreach ($search as $s) {
				if (trim($selector) === '') {
=======
	private function getCssSelectorPrecedence( $selector ) {
		$selectorKey = md5($selector);
		if ( ! isset($this->caches[ self::CACHE_KEY_SELECTOR ][ $selectorKey ]) ) {
			$precedence = 0;
			$value = 100;
			// ids: worth 100, classes: worth 10, elements: worth 1
			$search = array( '\\#','\\.','' );

			foreach ( $search as $s ) {
				if ( trim($selector) === '' ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
					break;
				}
				$number = 0;
				$selector = preg_replace('/' . $s . '\\w+/', '', $selector, -1, $number);
				$precedence += ($value * $number);
				$value /= 10;
			}
<<<<<<< HEAD
			$this->caches[self::CACHE_KEY_SELECTOR][$selectorKey] = $precedence;
		}

		return $this->caches[self::CACHE_KEY_SELECTOR][$selectorKey];
=======
			$this->caches[ self::CACHE_KEY_SELECTOR ][ $selectorKey ] = $precedence;
		}

		return $this->caches[ self::CACHE_KEY_SELECTOR ][ $selectorKey ];
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	}

	private function translateCssToXpath_callback( $matches ) {
		return strtolower($matches[0]);
	}

	/**
	 * Maps a CSS selector to an XPath query string.
	 *
	 * @see http://plasmasturm.org/log/444/
	 *
	 * @param string $cssSelector a CSS selector
	 *
	 * @return string the corresponding XPath selector
	 */
<<<<<<< HEAD
	private function translateCssToXpath($cssSelector)
	{
=======
	private function translateCssToXpath( $cssSelector ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		$paddedSelector = ' ' . $cssSelector . ' ';
		$lowercasePaddedSelector = preg_replace_callback(
			'/\\s+\\w+\\s+/',
			array( $this, 'translateCssToXpath_callback' ),
			$paddedSelector
		);
<<<<<<< HEAD

		$trimmedLowercaseSelector = trim($lowercasePaddedSelector);
		$xPathKey = md5($trimmedLowercaseSelector);
		if (!isset($this->caches[self::CACHE_KEY_XPATH][$xPathKey])) {
			$roughXpath = '//' . preg_replace(
				array_keys($this->xPathRules),
				$this->xPathRules,
				$trimmedLowercaseSelector
			);
			$xPathWithIdAttributeMatchers = preg_replace_callback(
				self::ID_ATTRIBUTE_MATCHER,
				array($this, 'matchIdAttributes'),
				$roughXpath
			);
			$xPathWithIdAttributeAndClassMatchers = preg_replace_callback(
				self::CLASS_ATTRIBUTE_MATCHER,
				array($this, 'matchClassAttributes'),
				$xPathWithIdAttributeMatchers
=======
		$trimmedLowercaseSelector = trim($lowercasePaddedSelector);
		$xpathKey = md5($trimmedLowercaseSelector);
		if ( ! isset($this->caches[ self::CACHE_KEY_XPATH ][ $xpathKey ]) ) {
			$cssSelectorMatches = array(
				'child'            => '/\\s+>\\s+/',
				'adjacent sibling' => '/\\s+\\+\\s+/',
				'descendant'       => '/\\s+/',
				':first-child'     => '/([^\\/]+):first-child/i',
				':last-child'      => '/([^\\/]+):last-child/i',
				'attribute only'   => '/^\\[(\\w+|\\w+\\=[\'"]?\\w+[\'"]?)\\]/',
				'attribute'        => '/(\\w)\\[(\\w+)\\]/',
				'exact attribute'  => '/(\\w)\\[(\\w+)\\=[\'"]?(\\w+)[\'"]?\\]/',
			);
			$xPathReplacements = array(
				'child'            => '/',
				'adjacent sibling' => '/following-sibling::*[1]/self::',
				'descendant'       => '//',
				':first-child'     => '\\1/*[1]',
				':last-child'      => '\\1/*[last()]',
				'attribute only'   => '*[@\\1]',
				'attribute'        => '\\1[@\\2]',
				'exact attribute'  => '\\1[@\\2="\\3"]',
			);

			$roughXpath = '//' . preg_replace($cssSelectorMatches, $xPathReplacements, $trimmedLowercaseSelector);

			$xpathWithIdAttributeMatchers = preg_replace_callback(
				self::ID_ATTRIBUTE_MATCHER,
				array( $this, 'matchIdAttributes' ),
				$roughXpath
			);
			$xpathWithIdAttributeAndClassMatchers = preg_replace_callback(
				self::CLASS_ATTRIBUTE_MATCHER,
				array( $this, 'matchClassAttributes' ),
				$xpathWithIdAttributeMatchers
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
			);

			// Advanced selectors are going to require a bit more advanced emogrification.
			// When we required PHP 5.3, we could do this with closures.
<<<<<<< HEAD
			$xPathWithIdAttributeAndClassMatchers = preg_replace_callback(
				'/([^\\/]+):nth-child\\(\\s*(odd|even|[+\\-]?\\d|[+\\-]?\\d?n(\\s*[+\\-]\\s*\\d)?)\\s*\\)/i',
				array($this, 'translateNthChild'),
				$xPathWithIdAttributeAndClassMatchers
			);
			$finalXpath = preg_replace_callback(
				'/([^\\/]+):nth-of-type\\(\s*(odd|even|[+\\-]?\\d|[+\\-]?\\d?n(\\s*[+\\-]\\s*\\d)?)\\s*\\)/i',
				array($this, 'translateNthOfType'),
				$xPathWithIdAttributeAndClassMatchers
			);

			$this->caches[self::CACHE_KEY_SELECTOR][$xPathKey] = $finalXpath;
		}
		return $this->caches[self::CACHE_KEY_SELECTOR][$xPathKey];
=======
			$xpathWithIdAttributeAndClassMatchers = preg_replace_callback(
				'/([^\\/]+):nth-child\\(\\s*(odd|even|[+\\-]?\\d|[+\\-]?\\d?n(\\s*[+\\-]\\s*\\d)?)\\s*\\)/i',
				array( $this, 'translateNthChild' ),
				$xpathWithIdAttributeAndClassMatchers
			);
			$finalXpath = preg_replace_callback(
				'/([^\\/]+):nth-of-type\\(\s*(odd|even|[+\\-]?\\d|[+\\-]?\\d?n(\\s*[+\\-]\\s*\\d)?)\\s*\\)/i',
				array( $this, 'translateNthOfType' ),
				$xpathWithIdAttributeAndClassMatchers
			);

			$this->caches[ self::CACHE_KEY_SELECTOR ][ $xpathKey ] = $finalXpath;
		}
		return $this->caches[ self::CACHE_KEY_SELECTOR ][ $xpathKey ];
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	}

	/**
	 * @param string[] $match
	 *
	 * @return string
	 */
<<<<<<< HEAD
	private function matchIdAttributes(array $match)
	{
=======
	private function matchIdAttributes( array $match ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		return ($match[1] !== '' ? $match[1] : '*') . '[@id="' . $match[2] . '"]';
	}

	/**
	 * @param string[] $match
	 *
	 * @return string
	 */
<<<<<<< HEAD
	private function matchClassAttributes(array $match)
	{
=======
	private function matchClassAttributes( array $match ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		return ($match[1] !== '' ? $match[1] : '*') . '[contains(concat(" ",@class," "),concat(" ","' .
			implode(
				'"," "))][contains(concat(" ",@class," "),concat(" ","',
				explode('.', substr($match[2], 1))
			) . '"," "))]';
	}

	/**
	 * @param string[] $match
	 *
	 * @return string
	 */
<<<<<<< HEAD
	private function translateNthChild(array $match)
	{
		$parseResult = $this->parseNth($match);

		if (isset($parseResult[self::MULTIPLIER])) {
			if ($parseResult[self::MULTIPLIER] < 0) {
				$parseResult[self::MULTIPLIER] = abs($parseResult[self::MULTIPLIER]);
				$xPathExpression = sprintf(
					'*[(last() - position()) mod %u = %u]/self::%s',
					$parseResult[self::MULTIPLIER],
					$parseResult[self::INDEX],
=======
	private function translateNthChild( array $match ) {
		$parseResult = $this->parseNth($match);

		if ( isset($parseResult[ self::MULTIPLIER ]) ) {
			if ( $parseResult[ self::MULTIPLIER ] < 0 ) {
				$parseResult[ self::MULTIPLIER ] = abs($parseResult[ self::MULTIPLIER ]);
				$xPathExpression = sprintf(
					'*[(last() - position()) mod %u = %u]/self::%s',
					$parseResult[ self::MULTIPLIER ],
					$parseResult[ self::INDEX ],
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
					$match[1]
				);
			} else {
				$xPathExpression = sprintf(
					'*[position() mod %u = %u]/self::%s',
<<<<<<< HEAD
					$parseResult[self::MULTIPLIER],
					$parseResult[self::INDEX],
=======
					$parseResult[ self::MULTIPLIER ],
					$parseResult[ self::INDEX ],
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
					$match[1]
				);
			}
		} else {
<<<<<<< HEAD
			$xPathExpression = sprintf('*[%u]/self::%s', $parseResult[self::INDEX], $match[1]);
=======
			$xPathExpression = sprintf('*[%u]/self::%s', $parseResult[ self::INDEX ], $match[1]);
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		}

		return $xPathExpression;
	}

	/**
	 * @param string[] $match
	 *
	 * @return string
	 */
<<<<<<< HEAD
	private function translateNthOfType(array $match)
	{
		$parseResult = $this->parseNth($match);

		if (isset($parseResult[self::MULTIPLIER])) {
			if ($parseResult[self::MULTIPLIER] < 0) {
				$parseResult[self::MULTIPLIER] = abs($parseResult[self::MULTIPLIER]);
				$xPathExpression = sprintf(
					'%s[(last() - position()) mod %u = %u]',
					$match[1],
					$parseResult[self::MULTIPLIER],
					$parseResult[self::INDEX]
=======
	private function translateNthOfType( array $match ) {
		$parseResult = $this->parseNth($match);

		if ( isset($parseResult[ self::MULTIPLIER ]) ) {
			if ( $parseResult[ self::MULTIPLIER ] < 0 ) {
				$parseResult[ self::MULTIPLIER ] = abs($parseResult[ self::MULTIPLIER ]);
				$xPathExpression = sprintf(
					'%s[(last() - position()) mod %u = %u]',
					$match[1],
					$parseResult[ self::MULTIPLIER ],
					$parseResult[ self::INDEX ]
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
				);
			} else {
				$xPathExpression = sprintf(
					'%s[position() mod %u = %u]',
					$match[1],
<<<<<<< HEAD
					$parseResult[self::MULTIPLIER],
					$parseResult[self::INDEX]
				);
			}
		} else {
			$xPathExpression = sprintf('%s[%u]', $match[1], $parseResult[self::INDEX]);
=======
					$parseResult[ self::MULTIPLIER ],
					$parseResult[ self::INDEX ]
				);
			}
		} else {
			$xPathExpression = sprintf('%s[%u]', $match[1], $parseResult[ self::INDEX ]);
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		}

		return $xPathExpression;
	}

	/**
	 * @param string[] $match
	 *
	 * @return int[]
	 */
<<<<<<< HEAD
	private function parseNth(array $match)
	{
		if (in_array(strtolower($match[2]), array('even', 'odd'), true)) {
			// we have "even" or "odd"
			$index = strtolower($match[2]) === 'even' ? 0 : 1;
			return array(self::MULTIPLIER => 2, self::INDEX => $index);
		}
		if (stripos($match[2], 'n') === false) {
			// if there is a multiplier
			$index = (int) str_replace(' ', '', $match[2]);
			return array(self::INDEX => $index);
		}

		if (isset($match[3])) {
=======
	private function parseNth( array $match ) {
		if ( in_array(strtolower($match[2]), array( 'even', 'odd' ), true) ) {
			// we have "even" or "odd"
			$index = strtolower($match[2]) === 'even' ? 0 : 1;
			return array( self::MULTIPLIER => 2, self::INDEX => $index );
		}
		if ( stripos($match[2], 'n') === false ) {
			// if there is a multiplier
			$index = (int) str_replace(' ', '', $match[2]);
			return array( self::INDEX => $index );
		}

		if ( isset($match[3]) ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
			$multipleTerm = str_replace($match[3], '', $match[2]);
			$index = (int) str_replace(' ', '', $match[3]);
		} else {
			$multipleTerm = $match[2];
			$index = 0;
		}

		$multiplier = str_ireplace('n', '', $multipleTerm);

<<<<<<< HEAD
		if ($multiplier === '') {
			$multiplier = 1;
		} elseif ($multiplier === '0') {
			return array(self::INDEX => $index);
=======
		if ( $multiplier === '' ) {
			$multiplier = 1;
		} elseif ( $multiplier === '0' ) {
			return array( self::INDEX => $index );
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		} else {
			$multiplier = (int) $multiplier;
		}

<<<<<<< HEAD
		while ($index < 0) {
			$index += abs($multiplier);
		}

		return array(self::MULTIPLIER => $multiplier, self::INDEX => $index);
=======
		while ( $index < 0 ) {
			$index += abs($multiplier);
		}

		return array( self::MULTIPLIER => $multiplier, self::INDEX => $index );
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	}

	/**
	 * Parses a CSS declaration block into property name/value pairs.
	 *
	 * Example:
	 *
	 * The declaration block
	 *
	 *   "color: #000; font-weight: bold;"
	 *
	 * will be parsed into the following array:
	 *
	 *   "color" => "#000"
	 *   "font-weight" => "bold"
	 *
	 * @param string $cssDeclarationsBlock the CSS declarations block without the curly braces, may be empty
	 *
	 * @return string[]
	 *         the CSS declarations with the property names as array keys and the property values as array values
	 */
<<<<<<< HEAD
	private function parseCssDeclarationsBlock($cssDeclarationsBlock)
	{
		if (isset($this->caches[self::CACHE_KEY_CSS_DECLARATIONS_BLOCK][$cssDeclarationsBlock])) {
			return $this->caches[self::CACHE_KEY_CSS_DECLARATIONS_BLOCK][$cssDeclarationsBlock];
=======
	private function parseCssDeclarationsBlock( $cssDeclarationsBlock ) {
		if ( isset($this->caches[ self::CACHE_KEY_CSS_DECLARATIONS_BLOCK ][ $cssDeclarationsBlock ]) ) {
			return $this->caches[ self::CACHE_KEY_CSS_DECLARATIONS_BLOCK ][ $cssDeclarationsBlock ];
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		}

		$properties = array();
		$declarations = preg_split('/;(?!base64|charset)/', $cssDeclarationsBlock);

<<<<<<< HEAD
		foreach ($declarations as $declaration) {
			$matches = array();
			if (!preg_match('/^([A-Za-z\\-]+)\\s*:\\s*(.+)$/', trim($declaration), $matches)) {
=======
		foreach ( $declarations as $declaration ) {
			$matches = array();
			if ( ! preg_match('/^([A-Za-z\\-]+)\\s*:\\s*(.+)$/', trim($declaration), $matches) ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
				continue;
			}

			$propertyName = strtolower($matches[1]);
			$propertyValue = $matches[2];
<<<<<<< HEAD
			$properties[$propertyName] = $propertyValue;
		}
		$this->caches[self::CACHE_KEY_CSS_DECLARATIONS_BLOCK][$cssDeclarationsBlock] = $properties;
=======
			$properties[ $propertyName ] = $propertyValue;
		}
		$this->caches[ self::CACHE_KEY_CSS_DECLARATIONS_BLOCK ][ $cssDeclarationsBlock ] = $properties;
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed

		return $properties;
	}

	/**
	 * Find the nodes that are not to be emogrified.
	 *
<<<<<<< HEAD
	 * @param \DOMXPath $xPath
	 *
	 * @return \DOMElement[]
	 */
	private function getNodesToExclude(DOMXPath $xPath)
	{
		$excludedNodes = array();
		foreach (array_keys($this->excludedSelectors) as $selectorToExclude) {
			foreach ($xPath->query($this->translateCssToXpath($selectorToExclude)) as $node) {
=======
	 * @param DoMXPath $xpath
	 *
	 * @return DoMElement[]
	 */
	private function getNodesToExclude( DoMXPath $xpath ) {
		$excludedNodes = array();
		foreach ( array_keys($this->excludedSelectors) as $selectorToExclude ) {
			foreach ( $xpath->query($this->translateCssToXpath($selectorToExclude)) as $node ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
				$excludedNodes[] = $node;
			}
		}

		return $excludedNodes;
	}
<<<<<<< HEAD

	/**
	 * Handles invalid xPath expression warnings, generated by process() method,
	 * during querying \DOMDocument and trigger \InvalidArgumentException
	 * with invalid selector.
	 *
	 * @param int $type
	 * @param string $message
	 * @param string $file
	 * @param int $line
	 * @param array $context
	 *
	 * @return bool always false
	 *
	 * @throws \InvalidArgumentException
	 */
	public function handleXpathError($type, $message, $file, $line, array $context)
	{
		if ($type === E_WARNING && isset($context['cssRule']['selector'])) {
			throw new InvalidArgumentException(
				sprintf(
					'%s in selector >> %s << in %s on line %s',
					$message,
					$context['cssRule']['selector'],
					$file,
					$line
				)
			);
		}

		// the normal error handling continues when handler return false
		return false;
	}
=======
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
}
