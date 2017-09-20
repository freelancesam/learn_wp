<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of fgchelper
 *
 * @author lexuanchien
 */
class Fgchelper {

    var $url = ''; //'http://pharmacy4less.proj01.dev.fgct.net';

    function __construct() {
        $url = $this->getPageURL();
        if (stripos($url, 'pharmacy4less.com.au')) {
            $this->url = str_replace('blog', '', $url);
        } elseif (stripos($url, 'yourchemistshop.com.au') || stripos($url, 'royyoungchemist.com.au') || stripos($url, 'revivogen.com.au') || stripos($url, 'completecompounding.com.au') || stripos($url, 'healthplusvitamins.com.au') || stripos($url, 'mywholesaleadvantage.com.au')) {
            if (@$_SERVER["HTTPS"] == "on") {
                $this->url = 'https://pharmacy4less.com.au';
                //$pageURL .= "s";
            } else {
                $this->url = 'http://pharmacy4less.com.au'; //str_replace('blog','',$url);
            }
        } else {
            $this->url = 'http://pharmacy4less.proj01.dev.fgct.net';
        }
        // XXX FIXME
        if (@$_SERVER["HTTPS"] == "on") {
            $this->url = 'https://www.pharmacy4less.com.au';
        } else {
            $this->url = 'http://www.pharmacy4less.com.au';
        }
        //echo $this->url;
    }

    //put your code here
    function getHeaderFooter() {
        include('simple_html_dom.php');
        $html = file_get_html($this->url);
        foreach ($html->find('div.header-container') as $e)
            $header_html = $e->outertext;
        foreach ($html->find('div.footer-container') as $e)
            $footer_html = $e->outertext;

        $footer_html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $footer_html);
        return array('header' => $header_html, 'footer' => $footer_html);
    }

    function getPageURL() {
        $pageURL = 'http';
        if (@$_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if (@$_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

}
