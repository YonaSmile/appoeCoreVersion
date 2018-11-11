<?php

namespace App;
class Template
{
    public static $pageDbData;
    public static $pageSlug;
    public static $pageHtmlContent;
    public static $pageHtmlZones;
    public static $html = '';

    public static $pageSecondaryDbData;
    public static $pageSecondarySlug;
    public static $pageSecondaryHtmlContent;
    public static $pageSecondaryHtmlZones;
    public static $htmlSecondary = '';

    public static $defaultCol = '12';
    public static $allMetaKeys = array();


    /**
     * Show content
     */
    public static function show()
    {
        echo !empty(self::$html) ? self::$html : self::$pageHtmlContent;
    }

    /**
     * @return string
     */
    public static function get()
    {
        return !empty(self::$html) ? self::$html : self::$pageHtmlContent;
    }

    /**
     * @return string
     */
    public static function getSecondary()
    {
        return !empty(self::$htmlSecondary) ? self::$htmlSecondary : self::$pageSecondaryHtmlContent;
    }


    /**
     * @param $pageSlug
     * @param $pageDbData
     * @param bool $getHtmlContent
     */
    public static function set($pageSlug, $pageDbData, $getHtmlContent = false)
    {
        self::$pageSlug = $pageSlug;
        self::$pageDbData = extractFromObjArr($pageDbData, 'metaKey');
        self::$pageHtmlContent = getFileContent(self::$pageSlug);

        //Check zones types
        if (preg_match_all("/{{(.*?)}}/", self::$pageHtmlContent, $match)) {

            //Check if return admin zone template or content
            if (!$getHtmlContent) {

                //Get zones types
                self::$pageHtmlZones = self::getZones($match[1]);

                foreach (self::$pageHtmlZones as $adminZone) {

                    self::$html .= self::buildHtmlZone($adminZone);
                }

            } else {

                //Get zones content
                self::$pageHtmlZones = $match;
                self::$html = self::buildHtmlContent();

            }
        }
    }

    /**
     * @param $pageSlug
     * @param $pageDbData
     */
    public static function setSecondary($pageSlug, $pageDbData)
    {
        self::$pageSecondarySlug = $pageSlug;
        self::$pageSecondaryDbData = extractFromObjArr($pageDbData, 'metaKey');
        self::$pageSecondaryHtmlContent = getFileContent(self::$pageSecondarySlug);

        //Check zones types
        if (preg_match_all("/{{(.*?)}}/", self::$pageSecondaryHtmlContent, $match)) {

            //Get zones content
            self::$pageSecondaryHtmlZones = $match;
            self::$htmlSecondary = self::buildSecondaryHtmlContent();
        }
    }

    /**
     * @return mixed
     */
    public static function buildHtmlContent()
    {

        foreach (self::$pageHtmlZones[1] as $i => $adminZone) {

            if (strpos($adminZone, '_')) {

                //Get data
                list($metaKey, $formType, $col, $group) = array_pad(explode('_', $adminZone), 4, '');

                //Set data
                self::$pageHtmlContent = str_replace(self::$pageHtmlZones[0][$i], sprintf('%s', !empty(self::$pageDbData[$metaKey]) ? htmlSpeCharDecode(self::$pageDbData[$metaKey]->metaValue) : ''), self::$pageHtmlContent);

            } else {

                self::$pageHtmlContent = str_replace(self::$pageHtmlZones[0][$i], '', self::$pageHtmlContent);
            }
        }

        return self::$pageHtmlContent;
    }

    /**
     * @return mixed
     */
    public static function buildSecondaryHtmlContent()
    {

        foreach (self::$pageSecondaryHtmlZones[1] as $i => $adminZone) {

            if (strpos($adminZone, '_')) {

                //Get data
                list($metaKey, $formType, $col, $group) = array_pad(explode('_', $adminZone), 4, '');

                //Set data
                self::$pageSecondaryHtmlContent = str_replace(self::$pageSecondaryHtmlZones[0][$i], sprintf('%s', !empty(self::$pageSecondaryDbData[$metaKey]) ? htmlSpeCharDecode(self::$pageSecondaryDbData[$metaKey]->metaValue) : ''), self::$pageSecondaryHtmlContent);

            } else {

                self::$pageSecondaryHtmlContent = str_replace(self::$pageSecondaryHtmlZones[0][$i], '', self::$pageSecondaryHtmlContent);
            }
        }

        return self::$pageSecondaryHtmlContent;
    }

    /**
     * @param $zone
     * @return string
     */
    public static function buildHtmlZone($zone)
    {

        $html = '';

        //Check for form types
        if (false !== strpos($zone, '_')) {

            //Get data
            list($metaKey, $formType, $col, $group) = array_pad(explode('_', $zone), 4, '');

            //Get input value
            $metaKeyDisplay = ucfirst(str_replace('-', ' ', $metaKey));
            $idCmsContent = !empty(self::$pageDbData[$metaKey]) ? self::$pageDbData[$metaKey]->id : '';
            $valueCmsContent = !empty(self::$pageDbData[$metaKey]) ? self::$pageDbData[$metaKey]->metaValue : '';

            //Display input zone
            $html .= '<div class="col-12 col-lg-' . (!empty($col) ? $col : self::$defaultCol) . ' my-2 templateZoneInput">';


            //Check unique input
            if (!in_array($metaKey, self::$allMetaKeys)) {

                //Display form input
                if (false !== strpos($formType, ':')) {

                    //Get form options
                    $options = explode(':', $formType);

                    //Get form type
                    $formType = array_shift($options);

                    if ($formType == 'select') {
                        $html .= \App\Form::select($metaKeyDisplay, $metaKey, array_combine($options, $options), $valueCmsContent, false, 'data-idcmscontent="' . $idCmsContent . '"');
                    }
                } else {
                    if ($formType == 'textarea') {
                        $html .= \App\Form::textarea($metaKeyDisplay, $metaKey, $valueCmsContent, 8, false, 'data-idcmscontent="' . $idCmsContent . '"', 'ckeditor');
                    } elseif ($formType == 'urlFile') {
                        $html .= \App\Form::text($metaKeyDisplay, $metaKey, 'url', $valueCmsContent, false, 250, 'data-idcmscontent="' . $idCmsContent . '" rel="cms-img-popover"', '', 'urlFile');
                    } else {
                        $html .= \App\Form::text($metaKeyDisplay, $metaKey, $formType, $valueCmsContent, false, 250, 'data-idcmscontent="' . $idCmsContent . '"', '', '');
                    }
                }

                array_push(self::$allMetaKeys, $metaKey);
            }

            $html .= '</div>';

        } else {
            $html .= $zone;
        }

        return $html;
    }

    /**
     * @param array $zones
     * @return array
     */
    public static function getZones(array $zones)
    {
        //Clean data
        $zones = cleanRequest($zones);

        //Zones types array
        $pageHtmlZonesTypes = array();

        foreach ($zones as $i => $adminZone) {

            //Check for form type
            if (false !== strpos($adminZone, '_')) {

                //Get data
                list($metaKey, $formType, $col, $group) = array_pad(explode('_', $adminZone), 4, '');

                //Check form type with options
                if (false !== strpos($formType, ':')) {

                    $options = explode(':', $formType);
                    $formType = array_shift($options);
                }

                //Check form authorised data
                if (self::isAuthorisedFormType($formType)) {

                    //Filter uniques form zones
                    if (!in_array($adminZone, $pageHtmlZonesTypes)) {
                        $pageHtmlZonesTypes[] = $adminZone;
                    }
                }

            } else {
                if (false !== strpos($adminZone, '#')) {

                    //Get data
                    list($htmlTag, $text) = array_pad(explode('#', $adminZone), 2, '');

                    //Get Container Classes
                    $extract = self::extractClassFromHtmlTag($htmlTag);
                    $htmlTag = $extract['tag'];
                    $class = $extract['class'];

                    //Check container authorised data
                    if (self::isAuthorisedHtmlContainer($htmlTag)) {

                        $pageHtmlZonesTypes[] = '<' . $htmlTag . ' class="templateZoneTag ' . $class . ' ">' . ucfirst($text) . '</' . $htmlTag . '>';
                    }

                } else {

                    //Get closed html tag condition
                    $closeTag = false;
                    if (false !== strpos($adminZone, '/')) {
                        $closeTag = true;
                        $adminZone = str_replace('/', '', $adminZone);
                    }

                    //Get Container Classes
                    $extract = self::extractClassFromHtmlTag($adminZone);
                    $htmlTag = $extract['tag'];
                    $class = $extract['class'];

                    //Check authorised html tag
                    if (self::isAuthorisedHtmlContainer($htmlTag)) {
                        $pageHtmlZonesTypes[] = '<' . ($closeTag ? '/' : '') . $htmlTag . ' class="templateZoneTag ' . $class . ' ">';
                    }

                }
            }
        }

        return $pageHtmlZonesTypes;
    }

    /**
     * @param $formType
     * @return bool
     */
    public static function isAuthorisedFormType($formType)
    {

        //Authorised form manage data
        $acceptedFormType = array('text', 'textarea', 'email', 'tel', 'url', 'color', 'number', 'date', 'select', 'radio', 'checkbox', 'urlFile');

        return in_array($formType, $acceptedFormType);
    }

    /**
     * @param $formType
     * @return bool
     */
    public static function isAuthorisedHtmlContainer($formType)
    {

        //Authorised HTML Container
        $acceptedHtmlContainer = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'strong', 'em', 'div', 'hr', 'br');

        return in_array($formType, $acceptedHtmlContainer);
    }

    /**
     * @param string $htmlTag
     * @return array
     */
    public static function extractClassFromHtmlTag($htmlTag = '')
    {
        $class = '';
        if (strpos($htmlTag, '.')) {
            list($htmlTag, $class) = explode('.', $htmlTag, 2);

            if (strpos($class, '.')) {
                $class = str_replace('.', ' ', $class);
            }
        }
        return array('tag' => $htmlTag, 'class' => $class);
    }

    /**
     * @return string
     */
    public static function showErrorPage()
    {
        return '<div class="container"><h4>' . trans('Cette page n\'existe pas') . '</h4></div>';
    }
}