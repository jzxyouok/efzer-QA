<?php
namespace Admin\Controller;
use Think\Controller;
class DashboardController extends Controller{
  public function index(){
    $Question = D('Questions');
    $Submission = D('Submissions');
    $Response = D('Responses');
    $submissions_count = $Submission->count();
    $this->assign('submissions_count',$submissions_count);
    $this->assign('questions_count',$Question->count());
    if($submissions_count == 0){
      $this->assign('responses_correct_average','N/A');
    }
    else{
      $this->assign('responses_correct_average',$Responses->avg('correct'));
    }
    $this->display();
  }
}
