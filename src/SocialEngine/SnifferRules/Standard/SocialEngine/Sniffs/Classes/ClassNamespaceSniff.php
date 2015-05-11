<?php

class SocialEngine_Sniffs_Classes_ClassNamespaceSniff implements PHP_CodeSniffer_Sniff
{
    protected $ignoreNamespace = [];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {

        $rawIgnoreNamespace = PHP_CodeSniffer::getConfigData('ignoreNamespace');
        if (!is_null($rawIgnoreNamespace)) {
            $this->ignoreNamespace = json_decode($rawIgnoreNamespace, true);
        }

        return [
            T_CLASS,
            T_INTERFACE,
            T_TRAIT,
        ];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param integer              $stackPtr  The position of the current token in
     *                                        the token stack.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $fileName = $phpcsFile->getFilename();
        if ($this->shouldIgnoreMissingNamespace($fileName)) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $errorData = array(strtolower($tokens[$stackPtr]['content']));
        $namespace = $phpcsFile->findPrevious(T_NAMESPACE, ($stackPtr - 1));
        if ($namespace === false) {
            $error = 'Each %s must be in a namespace of at least one level (a top-level vendor name)';
            $phpcsFile->addError($error, $stackPtr, 'MissingNamespace', $errorData);
        }
    }

    protected function shouldIgnoreMissingNamespace($filename)
    {
        $isIgnore = false;
        foreach ($this->ignoreNamespace as $ignored) {
            if (strpos($filename, $ignored) !== false) {
                $isIgnore = true;
                break;
            }
        }

        return $isIgnore;
    }
}
