<?php
namespace Admin\Controller;
use Think\Controller;
class DashboardController extends Controller{

  public function _before_index(){
    $this->check_login();
  }
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

  public function _before_questions_management(){
    $this->check_login();
  }
  public function questions_management(){
    $Question = D('Questions');
    if(IS_POST && IS_AJAX){
      if(empty($_POST['action'])){
        exit('Bad Request');
      }
      switch($_POST['action']){
        case 'add':
          if(empty($_POST['question_text'])||empty($_POST['question_choices']||empty($_POST['question_answer']))){
            echo json_encode(array('status'=>'failed',reason=>'请填写所有字段'));
            return;
          }
          $Question->question_text = I('post.question_text');
          $Question->question_choices = implode('|',explode(PHP_EOL,I('post.question_choices')));
          $Question->question_answer = I('post.question_answer');
          $Question->find($Question->add());
          $result['question_id'] = $Question->question_id;
          $result['question_text'] = $Question->question_text;
          $result['question_choices'] = implode(' | ',explode('|',$Question->question_choices));
          $result['question_answer'] = $Question->question_answer;
          echo json_encode(array('status'=>'success','result'=>$result));
          return;
        case 'delete':
          if(empty($_POST['question_id'])){
            exit(json_encode(array('status'=>'failed','reason'=>'没有传入 question_id')));
          }
          $Question->delete($_POST['question_id']);
          echo json_encode(array('status'=>'success','question_id'=>$_POST['question_id']));
          return;
        default:
          exit('Undefied Action');
      }
      return;
    }
    $this->assign('empty_message','<tr class="active"><th colspan="4">题库为空</th></tr>');
    $questions = $Question->select();
    for($i = 0; $i < count($questions); $i++){
      $questions[$i]['question_choices'] = implode(' | ', explode('|', $questions[$i]['question_choices']));
    }
    $this->assign('questions', $questions);
    $this->display();
  }

  public function login(){
    if(IS_POST){
      if(empty($_POST['pwd'])){
        $errmsg = '请输入管理员密码';
      }
      elseif(empty($_POST['captcha'])){
        $errmsg = '请输入验证码';
      }
      $Verify = new \Think\Verify();
      if(!$Verify->check($_POST['captcha'],'admin_login')){
        $errmsg = '验证码输入错误或已过期';
      }
      elseif($_POST['pwd'] != C('ADMIN_PASSWORD')){
        $errmsg = '管理密码错误';
      }
      if(!empty($errmsg)){
        $this->assign('errmsg',$errmsg);
        $this->display();
        return;
      }
      session('admin',true);
      $this->redirect('index', null, 0, '正在重定向到登录页面...');
      return;
    }
    $this->display();
  }

  private function is_login(){
    return session('?admin');
  }

  private function check_login(){
    if(!$this->is_login()){
      $this->redirect('login', null, 0, '正在跳转到登录页面...');
      exit();
    }
  }

  public function logout(){
    session('admin',null);
    $this->redirect('login', null, 0, '正在跳转到登录页面...');
  }

  public function showAdminLoginCaptcha(){
    $Verify = new \Think\Verify();
    $Verify->expire = 180;
    $Verify->length = 6;
    $Verify->imageW = 323;
    $Verify->imageH = 70;
    $Verify->fontSize = 25;
    $Verify->codeSet = '123456789QWERTYUIPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm';
    $Verify->bg = array(255,255,255);
    $Verify->entry('admin_login');
  }

}
