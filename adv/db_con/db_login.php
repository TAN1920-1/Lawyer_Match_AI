<?php
	if(isset($_POST['login']))
	{
		require('dbCon.php');
		$con = connect();
		
		$email = trim(mysqli_real_escape_string($con, $_POST['email']));
		$password = $_POST['password']; // Do NOT escape password for password_verify
		$role = isset($_POST['role']) ? mysqli_real_escape_string($con, $_POST['role']) : '';
        
        require_once('../includes/session_init.php');
        init_session(); // Use single session name
		
		if($role === 'Lawyer' || $role === 'FIRM_LAWYER'){
			$result = mysqli_query($con, "SELECT * FROM user INNER JOIN lawyer on user.u_id=lawyer.lawyer_id WHERE user.email = '" . $email. "' and (user.role='Lawyer' or user.role='FIRM_LAWYER') and user.status='Active' and user.is_active=1");
			if ($result && ($row = mysqli_fetch_array($result))) {
                if (password_verify($password, $row['password']) || $password === $row['password']) {
                    session_regenerate_id(true);
                    $_SESSION['email']=$email;
                    $_SESSION['password']=$password;
                    $_SESSION['first_Name']=$row['first_Name'];
                    $_SESSION['last_Name']=$row['last_Name'];
                    $_SESSION['lawyer_id']=$row['lawyer_id'];
                    $_SESSION['status']=$row['status'];
                    $_SESSION['role']=$row['role'];
                    $_SESSION['firm_id']=$row['firm_id'];
                    $_SESSION['login']=TRUE;
                    header("Location:../lawyer_dashboard.php?done");
                } else {
                    header('location:../login.php?error');
                }
			} else {
				$pend = mysqli_query($con, "SELECT status FROM user WHERE email = '" . $email . "' and (role='Lawyer' or role='FIRM_LAWYER') LIMIT 1");
				if ($pend && ($p = mysqli_fetch_array($pend)) && $p['status'] !== 'Active') {
					header('location:../login.php?pending=lawyer');
				} else {
					header('location:../login.php?error');
				}
			}
		} else if($role === 'User'){
			$result2 = mysqli_query($con, "SELECT * FROM user INNER JOIN client on user.u_id=client.client_id WHERE user.email = '" . $email. "' and user.role='User' and user.status='Active' and user.is_active=1");
			if ($result2 && ($row = mysqli_fetch_array($result2))) {
                if (password_verify($password, $row['password']) || $password === $row['password']) {
                    session_regenerate_id(true);
                    $_SESSION['email']=$email;
                    $_SESSION['password']=$password;
                    $_SESSION['first_Name']=$row['first_Name'];
                    $_SESSION['last_Name']=$row['last_Name'];
                    $_SESSION['client_id']=$row['client_id'];
                    $_SESSION['status']=$row['status'];
                    $_SESSION['role']='User';
                    $_SESSION['login']=TRUE;
                    header("Location:../user_dashboard.php?done");
                } else {
                    header('location:../login.php?error');
                }
			} else {
				$pend = mysqli_query($con, "SELECT status FROM user WHERE email = '" . $email . "' and role='User' LIMIT 1");
				if ($pend && ($p = mysqli_fetch_array($pend)) && $p['status'] !== 'Active') {
					header('location:../login.php?pending=user');
				} else {
					header('location:../login.php?error');
				}
			}
		} else if($role === 'Admin'){
			$result3 = mysqli_query($con,"SELECT * FROM user INNER JOIN administrator on user.u_id=administrator.administrator_id where user.email = '" . $email. "' and user.role='Admin' and user.is_active=1");
			if ($result3 && ($row = mysqli_fetch_array($result3))) {
                if (password_verify($password, $row['password']) || $password === $row['password']) {
                    session_regenerate_id(true);
                    $_SESSION['email']=$email;
                    $_SESSION['password']=$password;
                    $_SESSION['first_Name']=$row['first_Name'];
                    $_SESSION['last_Name']=$row['last_Name'];
                    $_SESSION['status']=isset($row['status']) ? $row['status'] : 'Active';
                    $_SESSION['role']='Admin';
                    $_SESSION['login']=TRUE;
                    header("Location:../admin_dashboard.php?done");
                } else {
                    header('location:../login.php?error');
                }
			} else {
				header('location:../login.php?error');
			}
		} else if($role === 'Firm' || $role === 'FIRM_ADMIN'){
			$result4 = mysqli_query($con,"SELECT * FROM user INNER JOIN firm on user.u_id=firm.firm_id WHERE user.email = '" . $email. "' and (user.role='Firm' or user.role='FIRM_ADMIN') and user.status='Active' and user.is_active=1");
			if ($result4 && ($row = mysqli_fetch_array($result4))) {
                if (password_verify($password, $row['password']) || $password === $row['password']) {
                    session_regenerate_id(true);
                    $_SESSION['email']=$email;
                    $_SESSION['password']=$password;
                    $_SESSION['first_Name']=$row['firm_name'];
                    $_SESSION['last_Name']='';
                    $_SESSION['firm_id']=$row['firm_id'];
                    $_SESSION['status']='Active';
                    $_SESSION['role']='FIRM_ADMIN';
                    $_SESSION['login']=TRUE;
                    header("Location:../firm_dashboard.php?done");
                } else {
                    header('location:../login.php?error');
                }
			} else {
				$firmCheck = mysqli_query($con,"SELECT password,status,u_id,first_Name,role FROM user WHERE email = '" . $email. "' and (role='Firm' or role='FIRM_ADMIN') and is_active=1");
				if($firmCheck && ($fc = mysqli_fetch_array($firmCheck))){
                    if (password_verify($password, $fc['password']) || $password === $fc['password']) {
                        if($fc['status']!='Active'){
                            header('location:../login.php?pending=firm');
                        } else {
                            session_regenerate_id(true);
                            $_SESSION['email']=$email;
                            $_SESSION['password']=$password;
                            $_SESSION['first_Name']=$fc['first_Name'];
                            $_SESSION['last_Name']='';
                            $_SESSION['firm_id']=$fc['u_id'];
                            $_SESSION['status']='Active';
                            $_SESSION['role']='FIRM_ADMIN';
                            $_SESSION['login']=TRUE;
                            header("Location:../firm_dashboard.php?done");
                        }
                    } else {
                        header('location:../login.php?error');
                    }
				} else {
					header('location:../login.php?error');
				}
			}
		} else {
			$result = mysqli_query($con, "SELECT * FROM user INNER JOIN lawyer on user.u_id=lawyer.lawyer_id WHERE user.email = '" . $email. "' and (user.role='Lawyer' or user.role='FIRM_LAWYER') and user.status='Active'");
			$result2 = mysqli_query($con, "SELECT * FROM user INNER JOIN client on user.u_id=client.client_id WHERE user.email = '" . $email. "' and user.role='User' and user.status='Active'");
			$result3 = mysqli_query($con,"SELECT * FROM user INNER JOIN administrator on user.u_id=administrator.administrator_id where user.email = '" . $email. "' and user.role='Admin'");
			$result4 = mysqli_query($con,"SELECT * FROM user INNER JOIN firm on user.u_id=firm.firm_id WHERE user.email = '" . $email. "' and (user.role='Firm' or user.role='FIRM_ADMIN') and user.status='Active'");
			if ($row = mysqli_fetch_array($result)) 
			{
                if (password_verify($password, $row['password']) || $password === $row['password']) {
                    session_regenerate_id(true);
                    $_SESSION['email']=$email;
                    $_SESSION['password']=$password;
                    $_SESSION['first_Name']=$row['first_Name'];
                    $_SESSION['last_Name']=$row['last_Name'];
                    $_SESSION['lawyer_id']=$row['lawyer_id'];
                    $_SESSION['status']=$row['status'];
                    $_SESSION['role']=$row['role'];
                    $_SESSION['login']=TRUE;
                    header("Location:../lawyer_dashboard.php?done");	
                } else {
                    header('location:../login.php?error');
                }
			}
			else if ($row = mysqli_fetch_array($result2)) {
                if (password_verify($password, $row['password']) || $password === $row['password']) {
                    session_regenerate_id(true);
                    $_SESSION['email']=$email;
                    $_SESSION['password']=$password;
                    $_SESSION['first_Name']=$row['first_Name'];
                    $_SESSION['last_Name']=$row['last_Name'];
                    $_SESSION['client_id']=$row['client_id'];
                    $_SESSION['status']=$row['status'];
                    $_SESSION['role']='User';
                    $_SESSION['login']=TRUE;
                    header("Location:../user_dashboard.php?done");
                } else {
                    header('location:../login.php?error');
                }
			}else if ($row = mysqli_fetch_array($result3)) {
                if (password_verify($password, $row['password']) || $password === $row['password']) {
                    session_regenerate_id(true);
                    $_SESSION['email']=$email;
                    $_SESSION['password']=$password;
                    $_SESSION['first_Name']=$row['first_Name'];
                    $_SESSION['last_Name']=$row['last_Name'];
                    $_SESSION['status']=$row['status'];
                    $_SESSION['role']='Admin';
                    $_SESSION['login']=TRUE;
                    header("Location:../admin_dashboard.php?done");
                } else {
                    header('location:../login.php?error');
                }
			}else if ($result4 && ($row = mysqli_fetch_array($result4))) {
                if (password_verify($password, $row['password']) || $password === $row['password']) {
                    session_regenerate_id(true);
                    $_SESSION['email']=$email;
                    $_SESSION['password']=$password;
                    $_SESSION['first_Name']=$row['firm_name'];
                    $_SESSION['last_Name']='';
                    $_SESSION['firm_id']=$row['firm_id'];
                    $_SESSION['status']='Active';
                    $_SESSION['role']='Firm';
                    $_SESSION['login']=TRUE;
                    header("Location:../firm_dashboard.php?done");
                } else {
                    header('location:../login.php?error');
                }
			}
			else 
			{
				$firmCheck = mysqli_query($con,"SELECT password,status,u_id,first_Name FROM user WHERE email = '" . $email. "' and (role='Firm' or role='FIRM_ADMIN')");
				if($firmCheck && ($fc = mysqli_fetch_array($firmCheck))){
                    if (password_verify($password, $fc['password']) || $password === $fc['password']) {
                        if($fc['status']!='Active'){
                            header('location:../login.php?pending=firm');
                        } else {
                            session_regenerate_id(true);
                            $_SESSION['email']=$email;
                            $_SESSION['password']=$password;
                            $_SESSION['first_Name']=$fc['first_Name'];
                            $_SESSION['last_Name']='';
                            $_SESSION['firm_id']=$fc['u_id'];
                            $_SESSION['status']='Active';
                            $_SESSION['role']='Firm';
                            $_SESSION['login']=TRUE;
                            header("Location:../firm_dashboard.php?done");
                        }
                    } else {
                        header('location:../login.php?error');
                    }
				} else {
					header('location:../login.php?error');
				}
			}
		}
	}
	else 
	{
		//echo "error msggggggggggg";
		
	header('location:../login.php?deactivate');
	}
?>		
