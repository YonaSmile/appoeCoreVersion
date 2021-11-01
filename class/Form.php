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
        $html = '<div class="form-floating">';
        $html .= '<select name = "' . $name . '" id = "' . $name . '" class="form-select" ' . $require . '>';

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

        $html .= '</select>';
        $html .= '<label for="' . $name . '" > ' . trans($title) . ' </label >';
        $html .= '</div>';

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
        $html = '<div class="form-floating">';
        $html .= '<select name = "' . $name . '" id = "' . $name . '" class="form-select" ' . $require . '>';

        for ($h = $hourBegin; $h < $hourEnd; $h++) {
            $h = $h < 10 ? '0' . $h : $h;
            for ($m = $minMin; $m <= $maxMin; $m += $jumpMin) {
                $m = $m < 10 ? '0' . $m : $m;
                $html .= '<option value="' . $h . ':' . $m . '" >' . $h . ':' . $m . '</option>';
            }

        }

        $html .= '</select>';
        $html .= '<label for="' . $name . '" > ' . trans($title) . ' </label >';
        $html .= '</div>';

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
     * @param string $otherClasses
     * @param bool $showTitle
     * @return string
     */
    public static function select($title, $name, array $values, $chosenValue = '', $require = false, $otherAttr = '', $compareVal = '', $compareOperator = '', $otherClasses = '', $showTitle = true)
    {

        $require = $require ? 'required="true"' : '';
        $html = '<div class="form-floating">';

        $html .= '<select ' . $otherAttr . ' name = "' . $name . '" id = "' . $name . '" class="form-select ' . $otherClasses . '" ' . $require . '>';

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

        $html .= '</select>';
        if ($showTitle) {
            $html .= '<label for="' . $name . '" > ' . trans($title) . ' </label>';
        }
        $html .= '</div>';
        return $html;
    }

    /**
     * @param $name
     * @param array $options
     * @return string
     */
    public static function input($name, array $options = [])
    {
        $options = array_merge([
            'title' => '',
            'type' => 'text',
            'val' => '',
            'required' => false,
            'length' => 300,
            'attr' => '',
            'class' => '',
            'help' => '',
            'placeholder' => '',
            'noTitle' => false,
        ], $options);

        $html = '';

        if (!$options['noTitle'] && !empty($options['title'])) {
            $html .= '<div class="form-floating">';
        }

        $html .= '<input type="' . $options['type'] . '" name = "' . $name . '" id = "' . $name . '" 
        value="' . $options['val'] . '" placeholder="' . trans($options['placeholder']) . '" 
        class="form-control ' . $options['class'] . '" ' . $options['attr'] . ' maxlength="' . $options['length'] . '" 
        ' . ($options['required'] ? 'required="true"' : '') . '>';

        if (!$options['noTitle'] && !empty($options['title'])) {
            $html .= '<label for="' . $name . '"> ' . trans($options['title']) . ' </label>';
            $html .= '</div>';
        }

        $html .= !empty($options['help']) ? $options['help'] : '';
        return $html;
    }


    /**
     * @param $name
     * @param array $options
     * @return string
     */
    public static function selectTimeSlot($name, array $options = [])
    {
        $options = array_merge([
            'title' => '',
            'type' => 'text',
            'required' => false,
            'startMin' => 0,
            'endMin' => 1440,
            'stepMin' => 30,
            'attr' => '',
            'class' => '',
            'noTitle' => false
        ], $options);

        $html = '<div class="form-floating">';
        $html .= '<select name = "' . $name . '" id = "' . $name . '" class="form-select ' . $options['class'] . '"
         ' . $options['attr'] . ' ' . ($options['required'] ? 'required="true"' : '') . '>';

        $time = $options['startMin'];
        while ($time <= $options['endMin']) {

            if ($time > $options['endMin']) {
                break;
            }

            $html .= '<option value="' . $time . '" >' . minutesToHours($time) . '</option>';
            $time += $options['stepMin'];
        }

        $html .= '</select>';
        if (!$options['noTitle'] && !empty($options['title'])) {
            $html .= '<label for="' . $name . '" > ' . trans($options['title']) . ' </label>';
        }
        $html .= '</div>';

        return $html;
    }


    /**
     * @param $name
     * @param array $options
     * @return string
     */
    public static function duration($name, array $options = [])
    {
        $options = array_merge([
            'title' => '',
            'minBegin' => 5,
            'minEnd' => 180,
            'minJump' => 5,
            'minTxt' => '',
            'required' => false,
            'val' => '',
            'attr' => '',
            'class' => '',
            'noTitle' => false
        ], $options);

        $html = '<div class="form-floating">';
        $html .= '<select name = "' . $name . '" id = "' . $name . '" class="form-select ' . $options['class'] . '" ' . $options['attr'] . ' ' . ($options['required'] ? 'required="true"' : '') . '>';

        if (empty($options['val'])) {
            $html .= '<option disabled="disabled" selected="selected" value="0">' . trans('Choisissez') . '...</option>';
        }

        if ($options['minBegin'] < $options['minEnd']) {
            for ($time = $options['minBegin']; $time <= $options['minEnd']; $time += $options['minJump']) {
                $html .= '<option value="' . $time . '" ' . ($options['val'] == $time ? 'selected' : '') . '>' . $time . ' ' . $options['minTxt'] . '</option>';
            }
        }
        $html .= '</select>';
        if (!$options['noTitle'] && !empty($options['title'])) {
            $html .= '<label for="' . $name . '" > ' . trans($options['title']) . ' </label>';
        }
        $html .= '</div>';

        return $html;
    }

    /**
     * @param $name
     * @param array $options
     * @return string
     */
    public static function switch($name, array $options = [])
    {
        $options = array_merge([
            'val' => '',
            'attr' => '',
            'class' => '',
            'parentClass' => '',
            'description' => '',
            'noTitle' => false
        ], $options);

        $html = '<div class="form-check form-switch ' . $options['parentClass'] . '">
        <input type="checkbox" ' . $options['attr'] . ' class="form-check-input ' . $options['class'] . '" 
        name="' . $name . '" id="' . $name . '" ' . ($options['val'] === 'true' ? 'checked' : '') . ' >';

        if (!$options['noTitle']) {
            $html .= '<label class="form-check-label" for="' . $name . '">' . $options['description'] . '</label>';
        }

        $html .= '</div>';
        return $html;
    }

    /**
     * @param $title
     * @param $name
     * @param array $options
     * @return string
     */
    public static function btn($title, $name, array $options = [])
    {
        $options = array_merge([
            'type' => 'submit',
            'attr' => '',
            'class' => 'btn-block btn-outline-primary'
        ], $options);

        $html = '<div class="form-group">';
        $html .= '<button type="' . $options['type'] . '" id="' . $name . '" name="' . $name . '" 
        class="btn ' . $options['class'] . '" ' . $options['attr'] . ' >' . trans($title) . '</button>';
        $html .= '</div>';
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
     * @param bool $noTitleWithPlaceholder
     * @return string
     */
    public static function text($title, $name, $type = 'text', $value = '', $require = false, $maxLength = 300, $othersAttrs = '', $helpInput = '', $otherClasses = '', $placeholder = '', $noTitleWithPlaceholder = true)
    {

        $require = $require ? 'required="true"' : '';
        $html = '<div class="form-floating">';
        $html .= '<input type="' . $type . '" name = "' . $name . '" id = "' . $name . '" value="' . $value . '" placeholder="' . trans($placeholder) . '" class="form-control ' . $otherClasses . '" ' . $othersAttrs . ' maxlength="' . $maxLength . '"' . $require . '>';
        if (empty($placeholder) || !$noTitleWithPlaceholder) {
            $html .= '<label for="' . $name . '" > ' . trans($title) . ' </label>';
        }
        $html .= '</div>';
        $html .= !empty($helpInput) ? $helpInput : '';
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
        $html = '<div class="form-floating">';
        $html .= '<textarea name = "' . $name . '" id = "' . $name . '" style="height:' . (($rows * 100) / 2) . 'px;" class="form-control ' . $otherClass . '" ' . $require . ' ' . $otherAttr . ' placeholder="' . $placeholder . '">' . $value . '</textarea>';
        $html .= '<label for="' . $name . '" class="form-label"> ' . trans($title) . ' </label>';
        $html .= '</div>';
        return $html;
    }


    /**
     * @param string $title
     * @param string $name
     * @param bool $require
     * @param string $otherAttr
     * @param string $otherClass
     * @return string
     */
    public static function file($title, $name, $require = false, $otherAttr = '', $otherClass = '')
    {

        $require = $require ? 'required="true"' : '';
        $html = '<label for="' . $name . '" > ' . trans($title) . ' </label>';
        $html .= '<input type="file" id="' . $name . '" name="' . $name . '" class="form-control ' . $otherClass . '" ' . $require . ' ' . $otherAttr . '>';
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
    public static function checkbox($title, $name, array $data, $compare = array(), $otherClasses = '')
    {

        $html = '<div class="form-group"><strong class="inputLabel border-bottom pb-1 mb-3">' . trans($title) . '</strong>';


        foreach ($data as $id => $value) {
            $checked = '';
            if (!isArrayEmpty($compare)) {
                if (array_key_exists($id, $compare)) {
                    $checked = 'checked="checked"';
                }
            }
            $html .= '<div class="form-check ' . $otherClasses . '">';
            $html .= '<input type="checkbox" class="form-check-input" id="' . $name . $id . '" name="' . $name . '[]" value="' . $id . '" 
			' . $checked . '><label class="form-check-label" for="' . $name . $id . '">' . $value;
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
     * @param string $otherClass
     * @param string $otherAttr
     * @return string
     */
    public static function radio($title, $name, array $data, $compare = '', $require = false, $otherClass = '', $otherAttr = '')
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
            $html .= '<div class="form-check ' . $otherClass . '">';
            $html .= '<input type="radio" class="form-check-input" name="' . $name . '" id="' . $name . $id . '" value="' . $id . '" 
			' . $checked . ' ' . $otherAttr . ' ' . $require . '><label class="form-check-label" for="' . $name . $id . '">' . $value;
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
    public static function submit($title, $name, $otherClass = 'bgColorPrimary', $otherAttr = '')
    {
        return '<button type="submit" id="' . $name . '" name="' . $name . '" ' . $otherAttr . ' 
        class="btn w-100 ' . $otherClass . '">' . trans($title) . '</button>';
    }

    /**
     * @param $name
     * @return string
     */
    public static function target($name)
    {
        $html = '<input type="hidden" name = "' . $name . '" id = "' . $name . '" value="' . $name . '">';
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