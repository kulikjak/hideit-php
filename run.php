<?php

require_once("utils.php");
session_start();

// evaluate page given as GET argument or index
if (isset($_GET['page']) && $_GET['page'])
  HIDEIT_EVAL($_GET['page']);
else
  HIDEIT_EVAL('index');
