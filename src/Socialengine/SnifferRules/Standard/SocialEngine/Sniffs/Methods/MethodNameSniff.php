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

        $rawAllowSnakeCaseMethodName = PHP_CodeSniffer::getConfigData('allowSnakeCaseMethodName');
        if (!is_null($rawAllowSnakeCaseMethodName)) {
            $this->allowSnakeCaseMethodName = json_decode($rawAllowSnakeCaseMethodName, true);
        }
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
        if (preg_match('|^__|', $methodName) !== 0) {
            $magicPart = strtolower(substr($methodName, 2));
            if (isset($this->magicMethods[$magicPart]) === true
                || isset($this->methodsDoubleUnderscore[$magicPart]) === true
            ) {
                return;
            }
        }

        $testName = ltrim($methodName, '_');
        $className = $phpcsFile->getDeclarationName($currScope);

        if ($this->isAllowSnakeCaseMethodName($className, $testName)) {
            if ($this->isUnderscoreName($testName) === false) {
                $error = 'Test Method name "%s" is not in underscore format';
                $errorData = array($className . '::' . $methodName);
                $phpcsFile->addError($error, $stackPtr, 'NotUnderscore', $errorData);
                $phpcsFile->recordMetric($stackPtr, 'SnakeCase method name', 'no');
            } else {
                $phpcsFile->recordMetric($stackPtr, 'SnakeCase method name', 'yes');
            }
        } elseif (PHP_CodeSniffer::isCamelCaps($testName, false, true, false) === false) {
            $error = 'Method name "%s" is not in camel caps format';
            $errorData = array($className . '::' . $methodName);
            $phpcsFile->addError($error, $stackPtr, 'NotCamelCaps', $errorData);
            $phpcsFile->recordMetric($stackPtr, 'CamelCase method name', 'no');
        } else {
            $phpcsFile->recordMetric($stackPtr, 'CamelCase method name', 'yes');
        }
    }

    private function isAllowSnakeCaseMethodName($className, $methodName)
    {
        foreach ($this->allowSnakeCaseMethodName as $classMethodPair) {
            $classOk = $this->checkClassHasValidSuffix($className, $classMethodPair['classSuffix']);
            $methodOk = $this->checkMethodHasValidPrefix($methodName, $classMethodPair['methodPrefix']);
            if ($classOk && $methodOk) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns true if the specified class suffix is in the class name.
     *
     * @param string $className
     * @param string $classSuffix
     *
     * @return boolean
     */
    private function checkClassHasValidSuffix($className, $classSuffix = null)
    {
        if ($classSuffix === '*') {
            return true;
        }
        $classSuffixPos = strrpos($className, $classSuffix);
        return $classSuffixPos === (strlen($className) - strlen($classSuffix));
    }

    /**
     * Returns true if the specified method prefix is in the method name.
     *
     * @param string $methodName
     * @param string $methodPrefix
     *
     * @return boolean
     */
    private function checkMethodHasValidPrefix($methodName, $methodPrefix = [])
    {
        if (in_array('*', $methodPrefix)) {
            return true;
        }
        foreach ($methodPrefix as $prefix) {
            if (strpos($methodName, $prefix) === 0) {
                return true;
            }
        }
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
