<?php

class SocialEngine_Sniffs_WhiteSpace_OperatorSpacingSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = [
        'PHP'
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        $comparison = PHP_CodeSniffer_Tokens::$comparisonTokens;
        $operators = PHP_CodeSniffer_Tokens::$operators;
        $assignment = PHP_CodeSniffer_Tokens::$assignmentTokens;
        $boolean = PHP_CodeSniffer_Tokens::$booleanOperators;

        return array_unique(array_merge($comparison, $operators, $assignment, $boolean));
    }

    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The current file being checked.
     * @param integer              $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $operatorCode = $tokens[$stackPtr]['code'];

        if ($operatorCode === T_BITWISE_AND) {
            // If its not a reference, then we expect one space either side of the
            // bitwise operator.
            if ($phpcsFile->isReference($stackPtr) === false) {
                // Check there is one space before the & operator.
                if ($tokens[($stackPtr - 1)]['code'] !== T_WHITESPACE) {
                    $error = 'Expected 1 space before "&" operator; 0 found';
                    $phpcsFile->addError($error, $stackPtr, 'NoSpaceBeforeAmp');
                } else {
                    if (strlen($tokens[($stackPtr - 1)]['content']) !== 1) {
                        $found = strlen($tokens[($stackPtr - 1)]['content']);
                        $error = sprintf('Expected 1 space before "&" operator; %s found', $found);

                        $phpcsFile->addError($error, $stackPtr, 'SpacingBeforeAmp');
                    }
                }

                // Check there is one space after the & operator.
                if ($tokens[($stackPtr + 1)]['code'] !== T_WHITESPACE) {
                    $error = 'Expected 1 space after "&" operator; 0 found';
                    $phpcsFile->addError($error, $stackPtr, 'NoSpaceAfterAmp');
                } else {
                    if (strlen($tokens[($stackPtr + 1)]['content']) !== 1) {
                        $found = strlen($tokens[($stackPtr + 1)]['content']);
                        $error = sprintf('Expected 1 space after "&" operator; %s found', $found);

                        $phpcsFile->addError($error, $stackPtr, 'SpacingAfterAmp');
                    }
                }
            }
        } else {
            if ($operatorCode === T_MINUS || $operatorCode === T_PLUS) {
                // Check minus spacing, but make sure we aren't just assigning
                // a minus value or returning one.
                $prev = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
                $prevCode = $tokens[$prev]['code'];
                if ($prevCode === T_RETURN) {
                    // Just returning a negative value; eg. return -1.
                    return;
                }

                if (in_array($prevCode, PHP_CodeSniffer_Tokens::$operators) === true) {
                    // Just trying to operate on a negative value; eg. ($var * -1).
                    return;
                }

                if (in_array($prevCode, PHP_CodeSniffer_Tokens::$comparisonTokens) === true) {
                    // Just trying to compare a negative value; eg. ($var === -1).
                    return;
                }

                // A list of tokens that indicate that the token is not
                // part of an arithmetic operation.
                $invalidTokens = [
                    T_COMMA,
                    T_OPEN_PARENTHESIS,
                    T_OPEN_SQUARE_BRACKET,
                    T_DOUBLE_ARROW,
                    T_COLON,
                    T_INLINE_THEN, // the ternary "?"
                    T_CASE,
                ];

                if (in_array($prevCode, $invalidTokens) === true) {
                    // Just trying to use a negative value; eg. myFunction($var, -2).
                    return;
                }

                $number = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);

                if (in_array($tokens[$number]['code'], array(T_LNUMBER, T_VARIABLE)) === true) {
                    $semi = $phpcsFile->findNext(T_WHITESPACE, ($number + 1), null, true);

                    if ($tokens[$semi]['code'] === T_SEMICOLON) {
                        if ($prev !== false && (in_array($tokens[$prev]['code'], PHP_CodeSniffer_Tokens::$assignmentTokens) === true)) {
                            // This is a negative assignment.
                            return;
                        }
                    }
                }
            }

            $operator = $tokens[$stackPtr]['content'];

            if ($tokens[($stackPtr - 1)]['code'] !== T_WHITESPACE) {
                $error = "Expected 1 space before \"$operator\"; 0 found";
                $phpcsFile->addError($error, $stackPtr, 'NoSpaceBefore');
            } elseif (strlen($tokens[($stackPtr - 1)]['content']) !== 1) {
                // Don't throw an error for assignments, because other standards allow
                // multiple spaces there to align multiple assignments.
                if (in_array($operatorCode, PHP_CodeSniffer_Tokens::$assignmentTokens) === false) {
                    $found = strlen($tokens[($stackPtr - 1)]['content']);
                    $error = sprintf('Expected 1 space before "%s"; %s found', $operator, $found);

                    $phpcsFile->addError($error, $stackPtr, 'SpacingBefore');
                }
            }

            if ($tokens[($stackPtr + 1)]['code'] !== T_WHITESPACE) {
                $error = "Expected 1 space after \"$operator\"; 0 found";
                $phpcsFile->addError($error, $stackPtr, 'NoSpaceAfter');
            } elseif (strlen($tokens[($stackPtr + 1)]['content']) !== 1) {
                $found = strlen($tokens[($stackPtr + 1)]['content']);
                $error = sprintf('Expected 1 space after "%s"; %s found', $operator, $found);

                $phpcsFile->addError($error, $stackPtr, 'SpacingAfter');
            }
        }
    }
}
