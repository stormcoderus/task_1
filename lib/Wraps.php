<?php

class Wraps
{
    public function wrapInTagLi($expression)
    {
        $liOpen = '<li>';
        $liClose = '</li>';

        return $liOpen.$expression.$liClose;
    }

    public function wrapInTagA($expression, $url)
    {
        $aOpen = "<a href=$url>";
        $aClose = '</a>';

        return $aOpen.$expression.$aClose;
    }
}
