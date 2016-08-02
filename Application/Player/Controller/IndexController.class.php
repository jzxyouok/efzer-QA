<?php
namespace Player\Controller;
use Think\Controller;
class IndexController extends Controller{

  public function index(){
    $Question = D('Questions');
    $Submission = M('Submissions');
    if(IS_POST){
      $Submission->submission_id = uniqid();
      $Submission->submission_time = date('Y-m-d H:i:s');
      echo "<meta charset=\"utf-8\">";
      foreach($_POST['question'] as $k => $response){
        $Response =  M('Responses');
        var_dump($response);
        $Response->submission_id = $Submission->submission_id;
        $Response->question_id = $response['question_id'];
        echo $this->get_answer($Response->question_id);
        $Response->response_choice = $response['answer'];
        $Response->correct = ($Response->response_choice == $this->get_answer($Response->question_id))?1:0;
        echo '<br />';
        echo $Response->correct;
        $Response->add();
      }
      $Response = D('Responses');
      $condition['submission_id'] = $Submission->submission_id;
      $Submission->score = $Response->where($condition)->sum('correct');
      $Submission->add();
      echo $Submission->score;
    }
    else{
      $questions = $Question->select();
      shuffle($questions);
      $questions = array_slice($questions,0,15);
      $questions_display = array();
      foreach($questions as $k => $question){
        $questions_display[$k] = $question;
        $questions_display[$k]['question_choices'] = array_map("trim",explode('|',$question['question_choices']));
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
