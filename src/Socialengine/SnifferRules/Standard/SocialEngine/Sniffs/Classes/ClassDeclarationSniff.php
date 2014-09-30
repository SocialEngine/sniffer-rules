<?php

class SocialEngine_Sniffs_Classes_ClassDeclarationSniff implements PHP_CodeSniffer_Sniff
{
    protected $ignoreNamespace = [];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        $this->ignoreNamespace = \Config::get('sniffer-rules::ignoreNamespace', []);
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
        $this->processSingleClass($phpcsFile, $stackPtr);
        $this->processHavingNamespace($phpcsFile, $stackPtr);
    }

    public function processSingleClass(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $errorData = array(strtolower($tokens[$stackPtr]['content']));

        $nextClass = $phpcsFile->findNext(array(T_CLASS, T_INTERFACE, T_TRAIT), ($stackPtr + 1));
        if ($nextClass !== false) {
            $error = 'Each %s must be in a file by itself';
            $phpcsFile->addError($error, $nextClass, 'MultipleClasses', $errorData);
        }
    }

    public function processHavingNamespace(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
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
            if (strpos($filename, $ignored) === 0) {
                $isIgnore = true;
                break;
            }
        }

        return $isIgnore;
    }
}
