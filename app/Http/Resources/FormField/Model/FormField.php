<?php

namespace App\Http\Resources\FormField\Model;

class FormField
{

    private string $fieldType;

    private string $fieldName;

    private string $fieldValue;

    /**
     * FormField constructor.
     * @param string $fieldType
     * @param string $fieldName
     * @param string $fieldValue
     */
    public function __construct(string $fieldType, string $fieldName, string $fieldValue = '')
    {
        $this->fieldType = $fieldType;
        $this->fieldName = $fieldName;
        $this->fieldValue = $fieldValue;
    }

    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return $this->fieldType;
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * @return string
     */
    public function getFieldValue(): string
    {
        return $this->fieldValue;
    }
}
