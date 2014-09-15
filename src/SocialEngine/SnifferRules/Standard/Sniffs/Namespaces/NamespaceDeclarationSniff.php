<?php

class SocialEngine_Sniffs_Namespaces_NamespaceDeclarationSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_NAMESPACE);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        for ($i = ($stackPtr + 1); $i < $phpcsFile->numTokens; $i++) {
            if ($tokens[$i]['line'] === $tokens[$stackPtr]['line']) {
                continue;
            }

            break;
        }

        // Make sure this Namespace declaration in same line of open tag.
        $prev = $phpcsFile->findPrevious(T_OPEN_TAG, ($stackPtr - 1));
        if ($tokens[$prev]['line'] !== $tokens[$stackPtr]['line']) {
            $error = 'The namespace declaration and open tag must be in same line';
            $phpcsFile->addError($error, $stackPtr, 'SameLine');
        }

        // The $i var now points to the first token on the line after the
        // namespace declaration, which must be a blank line.
        $next = $phpcsFile->findNext(T_WHITESPACE, $i, $phpcsFile->numTokens, true);
        if ($tokens[$next]['line'] === $tokens[$i]['line']) {
            $error = 'There must be one blank line after the namespace declaration';
            $phpcsFile->addError($error, $stackPtr, 'BlankLineAfter');
        }
    }
}
