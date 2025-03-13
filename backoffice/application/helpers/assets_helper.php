<?php

/**
 * Set Checkbox
 *
 * Let's you set the selected value of a checkbox via the value in the POST array.
 * If Form Validation is active it retrieves the info from the validation class
 *
 * @access  public
 * @param   string
 * @param   string
 * @param   bool
 * @return  string
 */
 
if ( ! function_exists('set_checkbox'))
{
    function set_checkbox($field = '', $value = '', $default = FALSE)
    {
        $OBJ =& _get_validation_object();

        if ($OBJ === FALSE)
        {
            if ( ! isset($_POST[$field]))
            {
                if (count($_POST) === 0 AND $default == TRUE)
                {
                    return ' checked="checked"';
                }
                return '';
            }

            $field = $_POST[$field];

            if (is_array($field))
            {
                if ( ! in_array($value, $field))
                {
                    return '';
                }
            }
            else
            {
                if (($field == '' OR $value == '') OR ($field != $value))
                {
                    return '';
                }
            }

            return ' checked="checked"';
        }

        return $OBJ->set_checkbox($field, $value, $default);
    }
}

/*
* Method to load css files into your project.
* @param array $css
*/

if ( ! function_exists('load_theme'))
{
    function load_theme($theme)
    {
	
        if (!is_array($theme))
        {     
		$theme = (array) $theme;
        }

        $return = '';
        foreach ($theme as $c)
        {
            $return .= '<link rel="stylesheet" href="' . base_url() . 'assets/themes/smoothness/' . $c . '.css"/>' . "\n";
        }
	
        return $return;
    }
}

/*
* Method to load css files into your project.
* @param array $css
*/

if ( ! function_exists('load_css'))
{
    function load_css($css)
    {
        if ( ! is_array($css))
        {
            $css = (array) $css;
        }

        $return = '';
        foreach ($css as $c)
        {
            $return .= '<link rel="stylesheet" href="' . base_url() . 'assets/css/' . $c . '.css"/>' . "\n";
        }
        return $return;
    }
}

/*
* Method to load scss files into your project.
* @param array $scss
*/

if ( ! function_exists('load_scss'))
{
    function load_scss($css)
    {
        if ( ! is_array($css))
        {
            $css = (array) $css;
        }

        $return = '';
        foreach ($css as $c)
        {
            $return .= '<link rel="stylesheet" href="' . base_url() . 'assets/css/' . $c . '.scss"/>' . "\n";
        }
        return $return;
    }
}

/*
* Method to load javascript files into your project.
* @param array $js
*/

if ( ! function_exists('load_js'))
{
    function load_js($js)
    {
        if ( ! is_array($js))
        {
            $js = (array) $js;
        }

        $return = '';
        foreach ($js as $j)
        {
            $return .= '<script type="text/javascript" src="' . base_url() . 'assets/scripts/' . $j . '.js"></script>' . "\n";
        }
        return $return;
    }
}

/*
* Method to load css files into your project.
* @param array $css
*/

if ( ! function_exists('load_bootstrap'))
{
    function load_bootstrap($css)
    {
        if ( ! is_array($css))
        {
            $css = (array) $css;
        }

        $return = '';
        foreach ($css as $c)
        {
            $return .= '<link rel="stylesheet" href="' . base_url() . 'assets/plugins/bootstrap/dist/css/' . $c . '.css"/>' . "\n";
        }
        return $return;
    }
}

/*
* Method to load css files into your project.
* @param array $css
*/

if ( ! function_exists('load_bootstrap_js'))
{
    function load_bootstrap_js($css)
    {
        if ( ! is_array($css))
        {
            $css = (array) $css;
        }

        $return = '';
        foreach ($css as $c)
        {
            $return .= '<link rel="stylesheet" href="' . base_url() . 'assets/plugins/bootstrap/dist/js/' . $c . '.js"/>' . "\n";
        }
        return $return;
    }
}