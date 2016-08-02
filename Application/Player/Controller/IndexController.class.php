<?php
namespace Player\Controller;
use Think\Controller;
class IndexController extends Controller{

  public function index(){
    $Question = D('Questions');
    $Submission = M('Submissions');
    if(cookie('submission_id')){
      $this->show_result(cookie('submission_id'));
    }
    elseif(IS_POST){
      $Submission->submission_id = uniqid();
      $Submission->submission_time = date('Y-m-d H:i:s');
      foreach($_POST['question'] as $k => $response){
        $Response =  M('Responses');
        $Response->submission_id = $Submission->submission_id;
        $Response->question_id = $response['question_id'];
        $Response->response_choice = $response['answer'];
        $Response->correct = ($Response->response_choice == $this->get_answer($Response->question_id))?1:0;
        $Response->add();
      }
      $Response = D('Responses');
      $condition['submission_id'] = $Submission->submission_id;
      $Submission->score = $Response->where($condition)->sum('correct');
      $Submission->add();
      cookie('submission_id',$condition['submission_id'],518400);
      $this->show_result($condition['submission_id']);
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

  private function show_result($submission_id){
    $Submission = M('Submissions');
    $Submission->find($submission_id);
    $score = $Submission->score;
    $upper_condition['score'] = array('gt',$score);
    $upper_count = $Submission->where($upper_condition)->count();
    $total_count = $Submission->count();
    $this->assign('percentage',round(100 * (1 - $upper_count / $total_count),2));
    $this->assign('correct_percentage',round(100 * $score / 15));
    if($score >= 15){
      $message = "你居然还不加入校友联络会？！";
    }
    elseif($score >= 13){
      $message = "加入校友会成为百分之百的二附中人吧！";
    }
    elseif($score >= 10){
      $message = "加入校友联络会提高二附中技能点吧！";
    }
    elseif($score >= 6){
      $message = "看来你很久没有关注二附中了啊，快关注校友联络会吧！";
    }
    else{
      $message = "你真的不是上海某中学派来的细作吗？！";
    }
    $this->assign('message',$message);
    $this->display('result');
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

  public function remove_cookie(){
    cookie('submission_id',null);
  }
}
