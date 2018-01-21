<?php

namespace MobileMaster\LaravelFileInput;

class Builder
{
    private $title;
    private $multiple;
    private $settings;
    private $suffix;

    public function createJsInit()
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

    public function addScripts()
    {
        $scripts = <<<EOC
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/vendor/mobilemaster/file-input/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="/vendor/mobilemaster/file-input/js/plugins/piexif.min.js" type="text/javascript"></script>
        <script src="/vendor/mobilemaster/file-input/js/plugins/sortable.min.js" type="text/javascript"></script>
        <script src="/vendor/mobilemaster/file-input/js/plugins/purify.min.js" type="text/javascript"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="/vendor/mobilemaster/file-input/js/fileinput.min.js"></script>
        <script src="/vendor/mobilemaster/file-input/themes/fa/theme.js"></script>
        <script src="/vendor/mobilemaster/file-input/js/locales/(lang).js"></script>
EOC;
        return $scripts;
    }

    public function getContainer()
    {
        $suffix = $this->getsuffix();
        $html = '';
        $id = "input-{$suffix}";
        $name = "input{$suffix}";
        if (!empty($this->title)) {
            $html .= "<label for=\"{$id}\">{$this->title}</label>";
        }
        $html .= "<div class=\"file-loading\">";
        $html .= "<input id=\"{$id}\" name=\"{$name}\" type=\"file\"";
        $html .= $this->multiple ? "multiple>" : ">";
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

    public function getDefaultSettings()
    {
        $settings = [];
        $settings['ajaxSettings'] = [
            'headers' => [
                'Accept' => 'application/json',
                'X-CSRF-TOKEN' => csrf_token()
            ]
        ];

        return $settings;
    }

    public function setDefaults()
    {
        $this->updateSettings($this->getDefaultSettings());
    }

    public function getSettings()
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

    public function getsuffix()
    {
        $suffix = $this->suffix ?: 'file';
        return $suffix;
    }

    public function withSuffix($value)
    {
        $this->setsuffix($value);

        return $this;
    }

    public function multiple($value)
    {
        $this->multiple = $value;
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
