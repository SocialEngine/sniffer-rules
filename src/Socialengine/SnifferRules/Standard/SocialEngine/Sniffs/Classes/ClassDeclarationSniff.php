<?php

class SocialEngine_Sniffs_Classes_ClassDeclarationSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
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
        if (isset($tokens[$stackPtr]['scope_closer']) === false) {
            return;
        }

        $errorData = array(strtolower($tokens[$stackPtr]['content']));

        $nextClass = $phpcsFile->findNext(array(T_CLASS, T_INTERFACE, T_TRAIT), ($stackPtr + 1));
        if ($nextClass !== false) {
            $error = 'Each %s must be in a file by itself';
            $phpcsFile->addError($error, $nextClass, 'MultipleClasses', $errorData);
        }
    }
}
