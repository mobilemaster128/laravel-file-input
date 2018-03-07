<?php

namespace MobileMaster\LaravelFileInput;

class Builder
{
    private $title;
    private $multiple;
    private $settings;
    private $suffix;
    private $name;
    private $required;

    private static $hasOne = false;

    private function createJsInit()
    {
        $suffix = $this->getsuffix();
        $id = "input-{$suffix}";
        $settings = json_encode($this->getSettings());
        $script = "var {$suffix}_file_input = $(\"input#{$id}[type=file]\");
        if ({$suffix}_file_input.length) {
            {$suffix}_file_input.fileinput({$settings});
        }";
        return $script;
    }

    private  function addStyles()
    {

        if (config('fileinput.bootstrap') === 3) { 
            $styles = '<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css" />>';
        } elseif (config('fileinput.bootstrap') === 4) { 
            $styles = '<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />>';
        }

        $styles .= '<link href="/vendor/mobilemaster/file-input/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />';
        
        return $styles;
    }

    private  function addScripts()
    {
        if (config('fileinput.jquery') !== NULL) { 
            $scripts = '<script src="//code.jquery.com/jquery-3.2.1.min.js"></script>';
        }
        $scripts .= <<<EOC
        <script src="/vendor/mobilemaster/file-input/js/plugins/piexif.min.js" type="text/javascript"></script>
        <script src="/vendor/mobilemaster/file-input/js/plugins/sortable.min.js" type="text/javascript"></script>
        <script src="/vendor/mobilemaster/file-input/js/plugins/purify.min.js" type="text/javascript"></script>
EOC;

        if (config('fileinput.bootstrap') === 3) { 
            $scripts .= '<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>';
        } elseif (config('fileinput.bootstrap') === 4) { 
            $scripts .= '<script src="//code.jquery.com/jquery-3.2.1.slim.min.js"></script>';
            $scripts .= '<script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>';
            $scripts .= '<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>';
        }

        $scripts .= <<<EOC
        <script src="/vendor/mobilemaster/file-input/js/fileinput.min.js"></script>
        <script src="/vendor/mobilemaster/file-input/themes/fa/theme.js"></script>
EOC;

        if (config('fileinput.lang') !== NULL) { 
            $scripts .= sprintf('<script src="/vendor/mobilemaster/file-input/js/locales/%s.js"></script>', config('fileinput.lang'));
        }
        
        return $scripts;
    }

    private function getContainer()
    {
        $suffix = $this->getsuffix();
        $html = '';
        $id = "input-{$suffix}";
        if (!empty($this->title)) {
            $html .= "<label for=\"{$id}\">{$this->title}</label>";
        }
        $html .= "<div class=\"file-loading\">";
        $html .= "<input id=\"{$id}\" type=\"file\"";
        $html .= $this->required ? " required" : "";
        $html .= empty($this->name) ? $this->multiple ? " name=\"file_data[]\"" : " name=\"file_data\"" : " name=\"{$this->name}\"";         
        $html .= $this->multiple ? " multiple >" : " >";
        $html .= '</div>';

        return $html;
    }

    public function createHtml()
    {
        $html = '';
        if (!Builder::$hasOne) {
            $html .= $this->addStyles();
            $html .= $this->addScripts();
        }
        Builder::$hasOne = true;
        $html .= $this->getContainer();
        $html .= '<script type="text/javascript">';
        $html .= $this->createJsInit();
        $html .= '</script>';

        return $html;
    }

    private function getDefaultSettings()
    {
        $settings = [];
        $settings['showCaption'] = true;
        $settings['showPreview'] = true;
        $settings['showRemove'] = true;
        $settings['showUpload'] = false;
        $settings['showCaption'] = true;
        $settings['ajaxSettings'] = [
            'headers' => [
                'Accept' => 'application/json',
                'X-CSRF-TOKEN' => csrf_token()
            ]
        ];

        return $settings;
    }

    private function setDefaults()
    {
        $this->updateSettings($this->getDefaultSettings());
    }

    private function getSettings()
    {
        $settings = $this->getDefaultSettings();

        $this->settings = $this->settings ?: [];

        foreach ($this->settings as $name => $value) {
            $settings[$name] = $value;
        }

        return $settings;
    }

    public function updateSettings(array $settings)
    {
        foreach ($settings as $name => $value) {
            $this->settings[$name] = $value;
        }
    }

    public function setsuffix($value)
    {
        $this->suffix = $value;
    }

    private function getsuffix()
    {
        $suffix = $this->suffix ?: 'file';
        return $suffix;
    }

    public function withSuffix($value)
    {
        $this->setsuffix($value);

        return $this;
    }

    public function withName($value)
    {
        $this->name = $value;

        return $this;
    }

    public function multiple($value)
    {
        $this->multiple = $value;

        return $this;
    }

    public function required()
    {
        $this->required = true;

        return $this;
    }

    public static function make(array $settings = null)
    {
        $instance = static::init($settings);

        return $instance->createHtml();
    }

    public static function init(array $settings = null)
    {
        $instance = new static();

        if ($settings) {
            $instance->updateSettings($settings);
        }

        return $instance;
    }
}
