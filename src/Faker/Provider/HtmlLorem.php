<?php

namespace Faker\Provider;

use Faker\Provider\HtmlLorem\HtmlNode;
use Faker\Provider\HtmlLorem\TextNode;

class HtmlLorem extends Base
{

    const MAX_LENGTH = 30;

    /**
     * @return string
     */
    public function randomHtml()
    {
        $html = new HtmlNode(HtmlNode::HTML);
        $head = new HtmlNode(HtmlNode::HEAD);
        $body = new HtmlNode(HtmlNode::BODY);
        $html->addNode($head);
        $html->addNode($body);
        $head->addNode(HtmlLorem::randomTitle());
        $body->addNode(HtmlLorem::loginForm());
        $this->randomSubTree($body, 8);
        return $html->compile();
    }

    private function randomSubTree(HtmlNode $root, $depth)
    {
        if ($depth <= 0) {
            return $root;
        }

        $siblings = mt_rand(1, $depth * 5);
        for ($i = 0; $i < $siblings; $i++) {
            if ($depth == 1) {
                $root->addNode($this->randomLeaf());
            } else {
                $sibling = new HtmlNode(HtmlNode::DIV);
                $this->addRandomAttribute($sibling);
                $this->randomSubTree($sibling, mt_rand(0, $depth--));
                $root->addNode($sibling);
            }

        };
        return $root;
    }

    private function randomLeaf()
    {
        $node = new TextNode();
        $rand = mt_rand(1, 10);
        switch($rand){
            case 1:
                $node = HtmlLorem::randomP();
                break;
            case 2:
                $node = HtmlLorem::randomText();
                break;
            case 3:
                $node = HtmlLorem::randomA();
                break;
            case 4:
                $node = HtmlLorem::randomSpan();
                break;
            case 5:
                $node = HtmlLorem::randomUL();
                break;
            case 6:
                $node = HtmlLorem::randomH1();
                break;
            case 7:
                $node = HtmlLorem::randomH2();
                break;
            case 8:
                $node = HtmlLorem::randomH3();
                break;
            case 9:
                $node = HtmlLorem::randomB();
                break;
            case 10:
                $node = HtmlLorem::randomI();
                break;
        }
        return $node;
    }

    private function addRandomAttribute(HtmlNode $node)
    {
        $rand = mt_rand(1, 100);
        if ($rand <= 50) {
            $node->addAttribute("class", Lorem::word());
        }
    }

    public static function randomP()
    {
        return HtmlNode::newInstance("p")
            ->addNode(TextNode::newInstance(Lorem::sentence(mt_rand(1, 30))));
    }

    public static function randomText()
    {
        return TextNode::newInstance(Lorem::sentence(mt_rand(1, 30)));
    }

    public static function randomA()
    {
        $text = TextNode::newInstance()
            ->setText(Lorem::word());

        return HtmlNode::newInstance()
            ->setType(HtmlNode::A)
            ->addAttribute("href", Internet::safeEmailDomain())
            ->addNode($text);
    }

    public function randomTitle()
    {
        $text = TextNode::newInstance()
            ->setText(Lorem::sentence(mt_rand(1, 3)));
        return HtmlNode::newInstance()
            ->setType(HtmlNode::TITLE)
            ->addNode($text);
    }

    public static function randomH1()
    {
        $text = TextNode::newInstance()
            ->setText(Lorem::sentence(mt_rand(1, 10)));
        return HtmlNode::newInstance(HtmlNode::H1)
            ->addNode($text);
    }

    public static function randomH2()
    {
        $text = TextNode::newInstance()
            ->setText(Lorem::sentence(mt_rand(1, 10)));
        return HtmlNode::newInstance(HtmlNode::H2)
            ->addNode($text);
    }

    public static function randomH3()
    {
        $text = TextNode::newInstance()
            ->setText(Lorem::sentence(mt_rand(1, 10)));
        return HtmlNode::newInstance(HtmlNode::H3)
            ->addNode($text);
    }

    public static function randomB()
    {
        $text = TextNode::newInstance()
            ->setText(Lorem::sentence(mt_rand(1, 10)));
        return HtmlNode::newInstance(HtmlNode::B)
            ->addNode($text);
    }

    public static function randomI()
    {
        $text = TextNode::newInstance()
            ->setText(Lorem::sentence(mt_rand(1, 10)));
        return HtmlNode::newInstance(HtmlNode::I)
            ->addNode($text);
    }

    public static function randomSpan()
    {
        $text = TextNode::newInstance()
            ->setText(Lorem::sentence(mt_rand(1, 30)));
        return HtmlNode::newInstance(HtmlNode::SPAN)
            ->addNode($text);
    }

    public static function loginForm()
    {
        $textInput = HtmlNode::newInstance(HtmlNode::INPUT)
            ->addAttribute("type", "text")
            ->addAttribute("id", "username");
        $textLabel = HtmlNode::newInstance(HtmlNode::LABEL)
            ->addAttribute("for", "username")
            ->addNode(TextNode::newInstance(Lorem::word()));
        $passwordInput = HtmlNode::newInstance(HtmlNode::INPUT)
            ->addAttribute("type", "password")
            ->addAttribute("id", "password");
        $passwordLabel = HtmlNode::newInstance(HtmlNode::LABEL)
            ->addAttribute("for", "password")
            ->addNode(TextNode::newInstance(Lorem::word()));
        $submit = HtmlNode::newInstance(HtmlNode::INPUT)
            ->addAttribute("type", "submit")
            ->addAttribute("value", Lorem::word());
        return HtmlNode::newInstance(HtmlNode::FORM)
            ->addAttribute("action", Internet::safeEmailDomain())
            ->addAttribute("method", "POST")
            ->addNode($textLabel)
            ->addNode($textInput)
            ->addNode($passwordLabel)
            ->addNode($passwordInput)
            ->addNode($submit);
    }

    public static function randomTable()
    {
        $cols = mt_rand(1, 6);
        $rows = mt_rand(1, 10);

        $table = HtmlNode::newInstance(HtmlNode::TABLE);
        $thead = HtmlNode::newInstance(HtmlNode::THEAD);
        $tbody = HtmlNode::newInstance(HtmlNode::TBODY);

        $table
            ->addNode($thead)
            ->addNode($tbody);

        $tr = HtmlNode::newInstance("tr");
        $thead->addNode($tr);
        for ($i = 0; $i < $cols; $i++) {
            $th  = HtmlNode::newInstance("th");
            $th->addNode(TextNode::newInstance(Lorem::sentence(mt_rand(1, 4))));
            $tr->addNode($th);
        }
        for ($i = 0; $i < $rows; $i++) {
            $tr = HtmlNode::newInstance("tr");
            $tbody->addNode($tr);
            for ($j = 0; $j < $cols; $j++) {
                $th  = HtmlNode::newInstance("td");
                $th->addNode(TextNode::newInstance(Lorem::sentence(mt_rand(1, 10))));
                $tr->addNode($th);
            }
        }
        return $table;
    }

    public static function randomUL()
    {
        $num = mt_rand(1, 10);
        $ul = HtmlNode::newInstance(HtmlNode::UL);
        for ($i = 0; $i < $num; $i++) {
            $li  = HtmlNode::newInstance(HtmlNode::LI);
            $li->addNode(TextNode::newInstance(Lorem::sentence(mt_rand(1, 4))));
            $ul->addNode($li);
        }
        return $ul;
    }
}