<?php

class SocialEngine_Sniffs_Classes_ClassDeclarationSniff implements PHP_CodeSniffer_Sniff
{
    protected $ignoredNamespace = [];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        $this->ignoredNamespace = [
            'app' . DIRECTORY_SEPARATOR . 'database'
        ];
        
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
        $tokens = $phpcsFile->getTokens();
        $errorData = array(strtolower($tokens[$stackPtr]['content']));

        $nextClass = $phpcsFile->findNext(array(T_CLASS, T_INTERFACE, T_TRAIT), ($stackPtr + 1));
        if ($nextClass !== false) {
            $error = 'Each %s must be in a file by itself';
            $phpcsFile->addError($error, $nextClass, 'MultipleClasses', $errorData);
        }
        
        $fileName = $phpcsFile->getFilename();
        if (version_compare(PHP_VERSION, '5.3.0') >= 0 && !$this->shouldIgnoredNamespace($fileName)) {
            $namespace = $phpcsFile->findPrevious(T_NAMESPACE, ($stackPtr - 1));
            if ($namespace === false) {
                $error = 'Each %s must be in a namespace of at least one level (a top-level vendor name)';
                $phpcsFile->addError($error, $stackPtr, 'MissingNamespace', $errorData);
            }
        }
    }

    protected function shouldIgnoredNamespace($filename)
    {
        $isIgnored = false;
        foreach ($this->ignoredNamespace as $ignored) {
            if (strpos($filename, $ignored) === 0) {
                $isIgnored = true;
                break;
            }
        }

        return $isIgnored;
    }
}
