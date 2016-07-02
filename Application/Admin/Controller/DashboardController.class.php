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
