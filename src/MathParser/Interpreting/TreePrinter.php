<?php
/*
 * @author      Frank Wikström <frank@mossadal.se>
 * @copyright   2015 Frank Wikström
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
*/

namespace MathParser\Interpreting;

use MathParser\Interpreting\Visitors\Visitor;
use MathParser\Parsing\Nodes\ExpressionNode;
use MathParser\Parsing\Nodes\NumberNode;
use MathParser\Parsing\Nodes\VariableNode;
use MathParser\Parsing\Nodes\FunctionNode;
use MathParser\Parsing\Nodes\ConstantNode;


/**
 * Simple string representation of an AST. Probably most
 * useful for debugging purposes.
 *
 * Implementation of a Visitor, transforming an AST into a string
 * representation of the tree.
 *
 * ## Example:
 *
 * ~~~{.php}
 * $parser = new StdMathParser();
 * $f = $parser->parse('exp(2x)+xy');
 * printer = new TreePrinter();
 * result = $f->accept($printer);    // Generates "(+ (exp (* 2 x)) (* x y))"
 * ~~~
 */
class TreePrinter implements Visitor
{
    public function visitExpressionNode(ExpressionNode $node)
    {
        $leftValue = $node->getLeft()->accept($this);
        $operator = $node->getOperator();

        // The operator and the right side are optional, remember?
        if (!$operator)
            return "$leftValue";

        $right = $node->getRight();

        if ($right) {
            $rightValue = $node->getRight()->accept($this);
            return "($operator, $leftValue, $rightValue)";
        } else {
            return "($operator, $leftValue)";
        }

    }

    public function visitNumberNode(NumberNode $node)
    {
        $val = $node->getValue();
        return "$val";
    }

    public function visitVariableNode(VariableNode $node)
    {
        return $node->getName();
    }

    public function visitFunctionNode(FunctionNode $node)
    {
        $functionName = $node->getName();
        $operand = $node->getOperand()->accept($this);

        return "$functionName($operand)";
    }

    public function visitConstantNode(ConstantNode $node)
    {
        return $node->getName();
    }
}
