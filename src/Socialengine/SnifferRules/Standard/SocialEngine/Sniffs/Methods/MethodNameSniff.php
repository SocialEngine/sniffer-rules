<?php

/**
 * Warehouse_Sniffs_Methods_MethodNameSniff.
 *
 * Ensures method names are defined using camel case exclude test method.
 * Ensures test method names are defined using underscore case.
 */
class SocialEngine_Sniffs_Methods_MethodNameSniff extends
PSR1_Sniffs_Methods_CamelCapsMethodNameSniff
{
    protected $allowSnakeCaseMethodName = [];

    /**
     * Constructs a SocialEngine_Sniffs_Methods_MethodNameSniff.
     */
    public function __construct()
    {
        parent::__construct();
        $this->allowSnakeCaseMethodName = \Config::get('sniffer-rules::allowSnakeCaseMethodName', []);
    }

    /**
     * Processes the tokens within the scope.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being processed.
     * @param int                  $stackPtr  The position where this token was
     *                                        found.
     * @param int                  $currScope The position of the current scope.
     *
     * @return void
     */
    protected function processTokenWithinScope(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $currScope)
    {
        $methodName = $phpcsFile->getDeclarationName($stackPtr);
        if ($methodName === null) {
            // Ignore closures.
            return;
        }

        // Ignore magic methods.
        $magicPart = strtolower(substr($methodName, 2));
        if (in_array($magicPart, array_merge($this->magicMethods, $this->methodsDoubleUnderscore)) !== false) {
            return;
        }

        $testName = ltrim($methodName, '_');
        $className = $phpcsFile->getDeclarationName($currScope);

        if ($this->isAllowSnakeCaseMethodName($className, $testName)) {
            if ($this->isUnderscoreName($testName) === false) {
                $error = 'Test Method name "%s" is not in underscore format';
                $errorData = array($className . '::' . $methodName);
                $phpcsFile->addError($error, $stackPtr, 'NotUnderscore', $errorData);
            }
        } elseif (PHP_CodeSniffer::isCamelCaps($testName, false, true, false) === false) {
            $error = 'Method name "%s" is not in camel caps format';
            $errorData = array($className . '::' . $methodName);
            $phpcsFile->addError($error, $stackPtr, 'NotCamelCaps', $errorData);
        }
    }

    private function isAllowSnakeCaseMethodName($className, $methodName)
    {
        $flage = false;
        foreach ($this->allowSnakeCaseMethodName as $value) {
            if (!empty($value['classSuffix'])) {
                $classSuffix = $value['classSuffix'];
                $classSuffixPos = strrpos($className, $classSuffix);
                $isValidClass = $classSuffixPos === (strlen($className) - strlen($classSuffix));
                if (!$isValidClass) {
                    continue;
                }

                $flage = empty($value['methodPrefix']);
            }

            if (!empty($value['methodPrefix'])) {
                foreach ($value['methodPrefix'] as $methodPrefix) {
                    $flage = strpos($methodName, $methodPrefix) === 0;
                    if ($flage) {
                        break;
                    }
                }
            }

            if ($flage) {
                break;
            }
        }

        return $flage;
    }

    /**
     * Returns true if the specified string is in the underscore caps format.
     *
     * @param string $string The string to verify.
     *
     * @return boolean
     */
    private function isUnderscoreName($string)
    {
        $validName = true;
        // Check that the name only contains legal characters.
        if (preg_match('/[^a-z_0-9]/', $string)) {
            $validName = false;
        }
        return $validName;
    }
}
