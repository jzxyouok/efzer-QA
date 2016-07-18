<?php
namespace Player\Controller;
use Think\Controller;
class IndexController extends Controller{

  private function get_answer($question_id){
    $cached_answer = F('Answer-' . $question_id);
    if($cached_answer === false){
      $Question = M('Questions');
      $Question->find($question_id);
      $cached_answer = $Question->question_answer;
      F('Answer-' . $question_id, $cached_answer);
    }
    return $cached_answer;
  }
}
