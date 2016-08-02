<?php
namespace Player\Controller;
use Think\Controller;
class IndexController extends Controller{

  public function index(){
    $Question = D('Questions');
    $Response =  D('Responses');
    $Submission = D('Submissions');
    if(IS_POST){
      $Submission->submission_id = uniqid();
      $Submission->submission_time = date('Y-m-d H:i:s');
      
    }
    else{
      $questions = $Question->select();
      shuffle($questions);
      $questions = array_slice($questions,0,15);
      $questions_display = array();
      foreach($questions as $k => $question){
        $questions_display[$k] = $question;
        $questions_display[$k]['question_choices'] = explode('|',$question['question_choices']);
      }
      $this->assign('question_list',$questions_display);
      $this->display();
    }
  }

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
