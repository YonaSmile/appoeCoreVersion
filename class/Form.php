<?php

namespace App;
class Form
{
    /**
     * @param $title
     * @param $name
     * @param bool $require
     * @param int $hourBegin
     * @param int $hourEnd
     * @param int $maxMin
     * @param int $jumpMin
     * @param string $chosenValue
     * @return string
     */
    public static function selectDuree($title, $name, $require = false, $hourBegin = 7, $hourEnd = 21, $maxMin = 45, $jumpMin = 15, $chosenValue = '')
    {

        $require = $require ? 'required="true"' : '';
        $html = '';
        $html .= '<div class="form-group" ><label for="' . $name . '" > ' . trans($title) . ' </label >';
        $html .= '<select name = "' . $name . '" id = "' . $name . '" class="form-control custom-select" ' . $require . '>';

        if (empty($chosenValue)) {
            $html .= '<option disabled="disabled" selected="selected" value="0">' . trans('Choisissez') . '...</option>';
        }

        for ($h = $hourBegin; $h < $hourEnd; $h++) {
            for ($m = $jumpMin; $m <= $maxMin; $m += $jumpMin) {
                $minutes = $m < 10 ? '0' . $m : $m;
                $heures = ($h == 0) ? '' : $h . 'h';
                $duree = $heures . $minutes;
                $html .= '<option value="' . $duree . '" ' . ($chosenValue == $duree ? 'selected' : '') . '>' . $duree . '</option>';
            }

        }

        $html .= '</select></div>';

        return $html;
    }

    /**
     * @param $title
     * @param $name
     * @param bool $require
     * @param int $hourBegin
     * @param int $hourEnd
     * @param int $maxMin
     * @param int $jumpMin
     * @param int $minMin
     * @return string
     */
    public static function selectTime($title, $name, $require = false, $hourBegin = 7, $hourEnd = 21, $maxMin = 45, $jumpMin = 15, $minMin = 0)
    {

        $require = $require ? 'required="true"' : '';
        $html = '';
        $html .= '<div class="form-group" ><label for="' . $name . '" > ' . trans($title) . ' </label >';
        $html .= '<select name = "' . $name . '" id = "' . $name . '" class="form-control custom-select" ' . $require . '>';

        for ($h = $hourBegin; $h < $hourEnd; $h++) {
            $h = $h < 10 ? '0' . $h : $h;
            for ($m = $minMin; $m <= $maxMin; $m += $jumpMin) {
                $m = $m < 10 ? '0' . $m : $m;
                $html .= '<option value="' . $h . ':' . $m . '" >' . $h . ':' . $m . '</option>';
            }

        }

        $html .= '</select></div>';

        return $html;
    }

    /**
     * @param $title
     * @param $name
     * @param array $values
     * @param string $chosenValue
     * @param bool $require
     * @param string $otherAttr
     * @param string $compareVal
     * @param string $compareOperator
     * @return string
     */
    public static function select($title, $name, array $values, $chosenValue = '', $require = false, $otherAttr = '', $compareVal = '', $compareOperator = '')
    {

        $require = $require ? 'required="true"' : '';
        $html = '';
        $html .= '<div class="form-group" ><label for="' . $name . '" > ' . trans($title) . ' </label >';
        $html .= '<select ' . $otherAttr . ' name = "' . $name . '" id = "' . $name . '" class="form-control custom-select" ' . $require . '>';

        if (empty($chosenValue)) {
            $html .= '<option disabled="disabled" selected="selected" value="0">' . trans('Choisissez') . '...</option>';
        }

        foreach ($values as $key => $value) {
            if (!empty($compareVal) && !empty($compareVal)) {
                if (self::compareValue($compareVal, $key, $compareOperator)) {
                    $html .= '<option value="' . $key . '" ' . ($chosenValue == $key ? 'selected' : '') . '>' . $value . '</option>';
                }
            } else {
                $html .= '<option value="' . $key . '" ' . ($chosenValue == $key ? 'selected' : '') . '>' . $value . '</option>';
            }
        }

        $html .= '</select></div>';

        return $html;
    }

    /**
     * @param $title
     * @param $name
     * @param string $type
     * @param string $value
     * @param bool $require
     * @param int $maxLength
     * @param string $othersAttrs
     * @param string $helpInput
     * @param string $otherClasses
     * @param string $placeholder
     * @return string
     */
    public static function text($title, $name, $type = 'text', $value = '', $require = false, $maxLength = 300, $othersAttrs = '', $helpInput = '', $otherClasses = '', $placeholder = '')
    {

        $require = $require ? 'required="true"' : '';

        $html = '';
        $html .= '<div class="form-group">';

        if (empty($placeholder)) {
            $html .= '<label for="' . $name . '" > ' . trans($title) . ' </label>';
        }

        $html .= '<input type="' . $type . '" name = "' . $name . '" id = "' . $name . '" value="' . $value . '" placeholder="' . $placeholder . '" class="form-control ' . $otherClasses . '" ' . $othersAttrs . ' maxlength="' . $maxLength . '"' . $require . '>';

        $html .= !empty($helpInput) ? $helpInput : '';

        $html .= '</div>';

        return $html;
    }

    /**
     * @param $title
     * @param $name
     * @param string $value
     * @param int $rows
     * @param bool $require
     * @param string $otherAttr
     * @param string $otherClass
     * @param string $placeholder
     * @return string
     */
    public static function textarea($title, $name, $value = '', $rows = 5, $require = false, $otherAttr = '', $otherClass = '', $placeholder = '')
    {

        $require = $require ? 'required="true"' : '';

        $html = '';
        $html .= '<div class="form-group">';

        if (empty($placeholder)) {
            $html .= '<label for="' . $name . '" > ' . trans($title) . ' </label>';
        }
        $html .= '<textarea name = "' . $name . '" id = "' . $name . '" rows="' . $rows . '" class="form-control ' . $otherClass . '" ' . $require . ' ' . $otherAttr . ' placeholder="' . $placeholder . '">' . $value . '</textarea>';
        $html .= '</div>';

        return $html;
    }

    /**
     * @param $name
     * @param bool $require
     * @return string
     */
    public static function file($name, $require = false)
    {

        $require = $require ? 'required="true"' : '';

        $html = '';
        $html .= '<div class="form-group"><label class="custom-file">';
        $html .= '<input type="file" id="' . $name . '" name="' . $name . '" class="custom-file-input" ' . $require . '><span class="custom-file-control"></span>';

        $html .= '</label></div>';

        return $html;
    }

    /**
     * @param $title
     * @param $name
     * @param array $data
     * @param string $compare
     * @param string $otherClasses
     * @return string
     */
    public static function checkbox($title, $name, array $data, $compare = '', $otherClasses = '')
    {

        $html = '';
        $html .= '<div class="form-group"><strong class="inputLabel border-bottom pb-1 mb-3">' . trans($title) . '</strong>';


        foreach ($data as $id => $value) {
            $checked = '';
            if (!empty($compare) && is_array($compare)) {
                if (array_key_exists($id, $compare)) {
                    $checked = 'checked="checked"';
                }
            }
            $html .= '<div class="custom-control custom-checkbox ' . $otherClasses . '">';
            $html .= '<input type="checkbox" class="custom-control-input" id="' . $name . $id . '" name="' . $name . '[]" value="' . $id . '" 
			' . $checked . '><label class="custom-control-label" for="' . $name . $id . '">' . $value;
            $html .= '</label></div>';

        }
        $html .= '</div>';

        return $html;
    }

    /**
     * @param $title
     * @param $name
     * @param array $data
     * @param string $compare
     * @param bool $require
     * @param string $otherClasses
     * @return string
     */
    public static function radio($title, $name, array $data, $compare = '', $require = false, $otherClasses = '')
    {

        $require = $require ? 'required="true"' : '';

        $html = '';
        $html .= '<div class="form-group"><strong class="inputLabel border-bottom pb-1 mb-3">' . trans($title) . '</strong>';

        foreach ($data as $id => $value) {
            $checked = '';
            if (isset($compare)) {
                if ($id == $compare) {
                    $checked = 'checked="checked"';
                }
            }
            $html .= '<div class="custom-control custom-radio ' . $otherClasses . '">';
            $html .= '<input type="radio" class="custom-control-input" name="' . $name . '" id="' . $name . $id . '" value="' . $id . '" 
			' . $checked . ' ' . $require . '><label class="custom-control-label" for="' . $name . $id . '">' . $value;
            $html .= '</label></div>';
        }
        $html .= '</div>';

        return $html;
    }

    /**
     * @param $title
     * @param $name
     * @param string $otherClass
     * @param string $otherAttr
     * @return string
     */
    public static function submit($title, $name, $otherClass = 'btn-outline-primary', $otherAttr = '')
    {
        $html = '';
        $html .= '<div class="form-group"><button type="submit" ';
        $html .= ' id="' . $name . '" name="' . $name . '" ';
        $html .= ' class="btn btn-block btn-lg ' . $otherClass . '" ';
        $html .= $otherAttr;
        $html .= ' >' . trans($title) . '</button></div>';

        return $html;
    }

    /**
     * @param $name
     * @return string
     */
    public static function target($name)
    {
        $html = '';
        $html .= '<input type="hidden" name = "' . $name . '" id = "' . $name . '" value="' . $name . '">';

        return $html;
    }

    /**
     * @param $val1
     * @param $val2
     * @param $comparator
     * @return bool
     */
    public static function compareValue($val1, $val2, $comparator)
    {

        switch ($comparator) {
            case '>':
                return $val1 > $val2 ? true : false;
                break;
            case '<':
                return $val1 < $val2 ? true : false;
                break;
            case '>=':
                return $val1 >= $val2 ? true : false;
                break;
            case '<=':
                return $val1 <= $val2 ? true : false;
                break;
            case '==':
                return $val1 == $val2 ? true : false;
                break;
            case '!=':
                return $val1 != $val2 ? true : false;
                break;
            default:
                return $val1 == $val2 ? true : false;
                break;
        }
    }
}