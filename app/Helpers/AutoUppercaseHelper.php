<?php

/**
 * Text Transform Helper
 * Server-side helper for text transformations
 *
 * @version 1.0.0
 * @author Laravel Helper
 */

if (!function_exists('auto_uppercase')) {
    /**
     * Transform text to uppercase
     *
     * @param string $text
     * @return string
     */
    function auto_uppercase($text)
    {
        return strtoupper(trim($text));
    }
}

if (!function_exists('auto_uppercase_array')) {
    /**
     * Transform array values to uppercase recursively
     *
     * @param array $data
     * @param array $excludeKeys Keys to exclude from transformation
     * @return array
     */
    function auto_uppercase_array(array $data, array $excludeKeys = [])
    {
        $transformed = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $excludeKeys)) {
                $transformed[$key] = $value;
            } elseif (is_string($value)) {
                $transformed[$key] = auto_uppercase($value);
            } elseif (is_array($value)) {
                $transformed[$key] = auto_uppercase_array($value, $excludeKeys);
            } else {
                $transformed[$key] = $value;
            }
        }

        return $transformed;
    }
}

if (!function_exists('form_uppercase_input')) {
    /**
     * Generate form input with auto uppercase class
     *
     * @param string $name
     * @param string $value
     * @param array $attributes
     * @return string
     */
    function form_uppercase_input($name, $value = '', $attributes = [])
    {
        $defaultAttributes = [
            'class' => 'form-control auto-uppercase',
            'name' => $name,
            'id' => $name,
            'value' => old($name, $value),
        ];

        $attributes = array_merge($defaultAttributes, $attributes);

        // Add auto-uppercase class if not present
        if (!str_contains($attributes['class'], 'auto-uppercase')) {
            $attributes['class'] .= ' auto-uppercase';
        }

        $attributeString = '';
        foreach ($attributes as $attr => $val) {
            $attributeString .= sprintf(' %s="%s"', $attr, htmlspecialchars($val));
        }

        return sprintf('<input type="text"%s>', $attributeString);
    }
}

if (!function_exists('form_uppercase_textarea')) {
    /**
     * Generate textarea with auto uppercase class
     *
     * @param string $name
     * @param string $value
     * @param array $attributes
     * @return string
     */
    function form_uppercase_textarea($name, $value = '', $attributes = [])
    {
        $defaultAttributes = [
            'class' => 'form-control auto-uppercase',
            'name' => $name,
            'id' => $name,
            'rows' => 3,
        ];

        $attributes = array_merge($defaultAttributes, $attributes);

        // Add auto-uppercase class if not present
        if (!str_contains($attributes['class'], 'auto-uppercase')) {
            $attributes['class'] .= ' auto-uppercase';
        }

        $attributeString = '';
        foreach ($attributes as $attr => $val) {
            if ($attr !== 'value') {
                $attributeString .= sprintf(' %s="%s"', $attr, htmlspecialchars($val));
            }
        }

        $content = old($name, $value);

        return sprintf('<textarea%s>%s</textarea>', $attributeString, htmlspecialchars($content));
    }
}

/**
 * Auto Uppercase Middleware
 * Transforms request data to uppercase automatically
 */
class AutoUppercaseMiddleware
{
    /**
     * Handle request
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $fields Comma-separated list of fields to transform
     * @return mixed
     */
    public function handle($request, \Closure $next, $fields = '')
    {
        if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('patch')) {
            $fieldsToTransform = $fields ? explode(',', $fields) : [];

            if (empty($fieldsToTransform)) {
                // Transform all text fields by default
                $data = $request->all();
                $transformed = $this->transformStringValues($data);
                $request->merge($transformed);
            } else {
                // Transform only specified fields
                $data = $request->only($fieldsToTransform);
                $transformed = $this->transformStringValues($data);
                $request->merge($transformed);
            }
        }

        return $next($request);
    }

    /**
     * Transform string values in array
     *
     * @param array $data
     * @return array
     */
    private function transformStringValues($data)
    {
        return array_map(function ($value) {
            if (is_string($value)) {
                return strtoupper(trim($value));
            }
            return $value;
        }, $data);
    }
}

/**
 * Auto Uppercase Request Class
 * Base request class with auto uppercase functionality
 */
abstract class AutoUppercaseRequest extends \Illuminate\Foundation\Http\FormRequest
{
    /**
     * Fields that should be auto-uppercase
     *
     * @var array
     */
    protected $uppercaseFields = [];

    /**
     * Fields that should be excluded from auto-uppercase
     *
     * @var array
     */
    protected $excludeFromUppercase = ['password', 'email', 'url'];

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $data = $this->all();

        if (!empty($this->uppercaseFields)) {
            // Transform only specified fields
            $fieldsToTransform = array_intersect_key($data, array_flip($this->uppercaseFields));
            $transformed = auto_uppercase_array($fieldsToTransform);
            $this->merge($transformed);
        } else {
            // Transform all string fields except excluded ones
            $transformed = auto_uppercase_array($data, $this->excludeFromUppercase);
            $this->replace($transformed);
        }
    }
}