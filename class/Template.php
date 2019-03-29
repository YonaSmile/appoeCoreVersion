<?php

namespace App;
class Template
{
    protected $pageDbData;
    protected $pageSlug;
    protected $pageHtmlContent;
    protected $pageHtmlZones;

    protected $defaultCol = '12';
    protected $allMetaKeys = array();
    protected $html = '';

    public function __construct($pageSlug, $pageDbData, $getHtmlContent = false)
    {
        $this->pageSlug = $pageSlug;
        $this->pageDbData = extractFromObjArr($pageDbData, 'metaKey');
        $this->pageHtmlContent = getFileContent($this->pageSlug);
        $this->set($getHtmlContent);
    }

    /**
     * Show content
     */
    public function show()
    {
        echo !empty($this->html) ? $this->html : '';
    }

    /**
     * @return string
     */
    public function get()
    {
        return !empty($this->html) ? $this->html : $this->pageHtmlContent;
    }

    /**
     * @param bool $getHtmlContent
     */
    public function set($getHtmlContent = false)
    {

        //Check zones types
        if (preg_match_all("/{{(.*?)}}/", $this->pageHtmlContent, $match)) {

            //Check if return admin zone template or content
            if (!$getHtmlContent) {

                //Get zones types
                $this->pageHtmlZones = $this->getZones($match[1]);

                foreach ($this->pageHtmlZones as $adminZone) {

                    $this->html .= $this->buildHtmlZone($adminZone);
                }

            } else {

                //Get zones content
                $this->pageHtmlZones = $match;
                $this->html = $this->buildHtmlContent();

            }
        }
    }

    /**
     * @return mixed
     */
    public function buildHtmlContent()
    {

        foreach ($this->pageHtmlZones[1] as $i => $adminZone) {

            if (strpos($adminZone, '_')) {

                //Get data
                list($metaKey, $formType, $col, $group) = array_pad(explode('_', $adminZone), 4, '');

                //Set data
                $this->pageHtmlContent = str_replace($this->pageHtmlZones[0][$i], sprintf('%s', !empty($this->pageDbData[$metaKey]) ? nl2br(htmlSpeCharDecode($this->pageDbData[$metaKey]->metaValue)) : ''), $this->pageHtmlContent);

            } else {

                $this->pageHtmlContent = str_replace($this->pageHtmlZones[0][$i], '', $this->pageHtmlContent);
            }
        }

        return $this->pageHtmlContent;
    }

    /**
     * @param $zone
     * @return string
     */
    public function buildHtmlZone($zone)
    {

        $html = '';

        //Check for form types
        if (false !== strpos($zone, '_')) {

            //Get data
            list($metaKey, $formType, $col, $group) = array_pad(explode('_', $zone), 4, '');

            //Get input value
            $metaKeyDisplay = ucfirst(str_replace('-', ' ', $metaKey));
            $idCmsContent = !empty($this->pageDbData[$metaKey]) ? $this->pageDbData[$metaKey]->id : '';
            $valueCmsContent = !empty($this->pageDbData[$metaKey]) ? $this->pageDbData[$metaKey]->metaValue : '';

            //Display input zone
            $html .= '<div class="col-12 col-lg-' . (!empty($col) ? $col : $this->defaultCol) . ' my-2 templateZoneInput">';


            //Check unique input
            if (!in_array($metaKey, $this->allMetaKeys)) {

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
                    if ($formType == 'textBig') {
                        $html .= \App\Form::textarea($metaKeyDisplay, $metaKey, $valueCmsContent, 8, false, 'data-idcmscontent="' . $idCmsContent . '"', '');
                    } elseif ($formType == 'textarea') {
                        $html .= \App\Form::textarea($metaKeyDisplay, $metaKey, $valueCmsContent, 8, false, 'data-idcmscontent="' . $idCmsContent . '"', 'ckeditor');
                    } elseif ($formType == 'urlFile') {
                        $html .= \App\Form::text($metaKeyDisplay, $metaKey, 'url', $valueCmsContent, false, 250, 'data-idcmscontent="' . $idCmsContent . '" rel="cms-img-popover"', '', 'urlFile');
                    } else {
                        $html .= \App\Form::text($metaKeyDisplay, $metaKey, $formType, $valueCmsContent, false, 250, 'data-idcmscontent="' . $idCmsContent . '"', '', '');
                    }
                }

                array_push($this->allMetaKeys, $metaKey);
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
    public function getZones(array $zones)
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
                if ($this->isAuthorisedFormType($formType)) {

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
                    $extract = $this->extractClassFromHtmlTag($htmlTag);
                    $htmlTag = $extract['tag'];
                    $class = $extract['class'];

                    //Check container authorised data
                    if ($this->isAuthorisedHtmlContainer($htmlTag)) {

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
                    $extract = $this->extractClassFromHtmlTag($adminZone);
                    $htmlTag = $extract['tag'];
                    $class = $extract['class'];

                    //Check authorised html tag
                    if ($this->isAuthorisedHtmlContainer($htmlTag)) {
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
    public function isAuthorisedFormType($formType)
    {

        //Authorised form manage data
        $acceptedFormType = array('text', 'textarea', 'textBig', 'email', 'tel', 'url', 'color', 'number', 'date', 'select', 'radio', 'checkbox', 'urlFile');

        return in_array($formType, $acceptedFormType);
    }

    /**
     * @param $formType
     * @return bool
     */
    public function isAuthorisedHtmlContainer($formType)
    {

        //Authorised HTML Container
        $acceptedHtmlContainer = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'strong', 'em', 'div', 'hr', 'br');

        return in_array($formType, $acceptedHtmlContainer);
    }

    /**
     * @param string $htmlTag
     * @return array
     */
    public function extractClassFromHtmlTag($htmlTag = '')
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
    public function showErrorPage()
    {
        return '<div class="container"><h4>' . trans('Cette page n\'existe pas') . '</h4></div>';
    }
}