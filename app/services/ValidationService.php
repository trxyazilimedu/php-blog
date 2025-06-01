<?php

class ValidationService
{
    private $errors = [];

    /**
     * Validation kurallarını çalıştırma
     */
    public function validate($data, $rules)
    {
        $this->errors = [];

        foreach ($rules as $field => $ruleSet) {
            $value = $data[$field] ?? null;
            $fieldRules = is_string($ruleSet) ? explode('|', $ruleSet) : $ruleSet;

            foreach ($fieldRules as $rule) {
                $this->applyRule($field, $value, $rule, $data);
            }
        }

        return empty($this->errors);
    }

    /**
     * Tek bir kural uygulama
     */
    private function applyRule($field, $value, $rule, $allData)
    {
        $ruleParts = explode(':', $rule);
        $ruleName = $ruleParts[0];
        $ruleValue = $ruleParts[1] ?? null;

        switch ($ruleName) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->addError($field, "{$field} alanı zorunludur.");
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "{$field} geçerli bir e-posta adresi olmalıdır.");
                }
                break;

            case 'min':
                if (!empty($value) && strlen($value) < $ruleValue) {
                    $this->addError($field, "{$field} en az {$ruleValue} karakter olmalıdır.");
                }
                break;

            case 'max':
                if (!empty($value) && strlen($value) > $ruleValue) {
                    $this->addError($field, "{$field} en fazla {$ruleValue} karakter olmalıdır.");
                }
                break;

            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, "{$field} sayısal bir değer olmalıdır.");
                }
                break;

            case 'integer':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_INT)) {
                    $this->addError($field, "{$field} tam sayı olmalıdır.");
                }
                break;

            case 'unique':
                if (!empty($value)) {
                    $parts = explode(',', $ruleValue);
                    $table = $parts[0];
                    $column = $parts[1] ?? $field;
                    $exceptId = $parts[2] ?? null;

                    if ($this->isUnique($table, $column, $value, $exceptId)) {
                        $this->addError($field, "{$field} daha önce kullanılmış.");
                    }
                }
                break;

            case 'confirmed':
                $confirmField = $field . '_confirmation';
                if ($value !== ($allData[$confirmField] ?? null)) {
                    $this->addError($field, "{$field} doğrulama alanı eşleşmiyor.");
                }
                break;

            case 'in':
                $allowedValues = explode(',', $ruleValue);
                if (!empty($value) && !in_array($value, $allowedValues)) {
                    $this->addError($field, "{$field} geçersiz bir değer içeriyor.");
                }
                break;

            case 'regex':
                if (!empty($value) && !preg_match($ruleValue, $value)) {
                    $this->addError($field, "{$field} geçersiz format.");
                }
                break;

            case 'date':
                if (!empty($value) && !strtotime($value)) {
                    $this->addError($field, "{$field} geçerli bir tarih olmalıdır.");
                }
                break;

            case 'url':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                    $this->addError($field, "{$field} geçerli bir URL olmalıdır.");
                }
                break;
        }
    }

    /**
     * Benzersizlik kontrolü
     */
    private function isUnique($table, $column, $value, $exceptId = null)
    {
        $db = Database::getInstance();
        
        $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = ?";
        $params = [$value];

        if ($exceptId) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();

        return $result['count'] > 0;
    }

    /**
     * Hata ekleme
     */
    private function addError($field, $message)
    {
        $this->errors[$field][] = $message;
    }

    /**
     * Tüm hataları alma
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Belirli alan hatalarını alma
     */
    public function getError($field)
    {
        return $this->errors[$field] ?? [];
    }

    /**
     * İlk hatayı alma
     */
    public function getFirstError($field = null)
    {
        if ($field) {
            return $this->errors[$field][0] ?? null;
        }

        foreach ($this->errors as $fieldErrors) {
            return $fieldErrors[0] ?? null;
        }

        return null;
    }

    /**
     * Hata var mı kontrolü
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }

    /**
     * Belirli alanda hata var mı
     */
    public function hasError($field)
    {
        return isset($this->errors[$field]) && !empty($this->errors[$field]);
    }

    /**
     * Hataları temizleme
     */
    public function clearErrors()
    {
        $this->errors = [];
    }
}
