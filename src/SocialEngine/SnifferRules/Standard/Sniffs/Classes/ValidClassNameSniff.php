<?php

class SocialEngine_Sniffs_Classes_ValidClassNameSniff implements PHP_CodeSniffer_Sniff
{
    protected $ignoreClassPrefix = 'SocialEngine_Sniffs_';

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_CLASS,
            T_INTERFACE,
        );
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The current file being processed.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['scope_opener']) === false) {
            $error = 'Possible parse error: %s missing opening or closing brace';
            $data = array($tokens[$stackPtr]['content']);
            $phpcsFile->addWarning($error, $stackPtr, 'MissingBrace', $data);
            return;
        }

        // Determine the name of the class or interface. Note that we cannot
        // simply look for the first T_STRING because a class name
        // starting with the number will be multiple tokens.
        $opener = $tokens[$stackPtr]['scope_opener'];
        $nameStart = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), $opener, true);
        $nameEnd = $phpcsFile->findNext(T_WHITESPACE, $nameStart, $opener);
        $name = trim($phpcsFile->getTokensAsString($nameStart, ($nameEnd - $nameStart)));

        // Check for camel caps format. also ignoring the Code Sniffer Classes from camel caps format checks
        $valid = PHP_CodeSniffer::isCamelCaps($name, true, true, false);
        if ($valid === false  && (strpos($name, $this->ignoreClassPrefix) !== 0)) {
            $type = ucfirst($tokens[$stackPtr]['content']);
            $error = '%s name "%s" is not in camel caps format';
            $data = array(
                $type,
                $name,
            );
            $phpcsFile->addError($error, $stackPtr, 'NotCamelCaps', $data);
        }
    }
}
