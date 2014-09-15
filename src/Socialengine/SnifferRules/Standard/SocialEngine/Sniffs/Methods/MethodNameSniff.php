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
    protected $testMethodPrefix = 'test';
    protected $testClassSuffix = 'Test';

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
        $testClassSuffixPos = strrpos($className, $this->testClassSuffix);
        $isTestClass = $testClassSuffixPos === (strlen($className) - strlen($this->testClassSuffix));
        if ($isTestClass && strpos($methodName, $this->testMethodPrefix) === 0) {
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
    
    /**
     * Returns true if the specified string is in the underscore caps format.
     *
     * @param string $string The string to verify.
     *
     * @return boolean
     */
    protected function isUnderscoreName($string)
    {
        // If there are space in the name, it can't be valid.
        if (strpos($string, ' ') !== false) {
            return false;
        }

        $validName = true;
        $nameBits  = explode('_', $string);
        
        if ($string{0} === strtoupper($string{0})) {
            // Name does not begin with a capital letter.
            $validName = false;
        } else {
            foreach ($nameBits as $bit) {

                if ($bit !== '' && !is_numeric($bit{0}) && $bit{0} === strtoupper($bit{0})) {
                    $validName = false;
                    break;
                }
            }
        }

        return $validName;

    }
}
