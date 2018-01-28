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

    private function addScripts()
    {
        $scripts = <<<EOC
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('vendor/mobilemaster/fileinput/css/fileinput.min.css') }}" media="all" rel="stylesheet" type="text/css" />
        <script src="//code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="{{ asset('vendor/mobilemaster/fileinput/js/plugins/piexif.min.js') }}" type="text/javascript"></script>
        <script src={{ asset('vendor/mobilemaster/fileinput/js/plugins/sortable.min.js') }}" type="text/javascript"></script>
        <script src={{ asset('vendor/mobilemaster/fileinput/js/plugins/purify.min.js') }}" type="text/javascript"></script>
        <script src={{ asset('/maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js') }}"></script>
        <script src={{ asset('vendor/mobilemaster/fileinput/js/fileinput.min.js') }}"></script>
        <script src={{ asset('vendor/mobilemaster/fileinput/themes/fa/theme.js') }}"></script>
        @isset(config('fileinput.lang'))
        <script src={{ asset('vendor/mobilemaster/fileinput/js/locales/' . config('fileinput.lang'). '.js') }}"></script>
        @endisset
EOC;
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
        $html .= $this->addScripts();
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
