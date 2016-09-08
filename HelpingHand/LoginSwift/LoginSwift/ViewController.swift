//
//  ViewController.swift
//  LoginSwift
//
//  Created by Griselda Cuenca on 8/26/16.
//  Copyright © 2016 Griselda Cuenca. All rights reserved.
//

import UIKit

class ViewController: UIViewController {

    @IBOutlet weak var txtUser: UITextField!
    @IBOutlet weak var txtPass: UITextField!
    var loginSucceeded = false
    let url = "http://10.0.0.5/ayudame/index.php"
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }

    @IBAction func perforLogin(sender: UIButton) {
        let result : (Bool, String, String) = validateData()
        if result.0 {
            HTTPHelper().post(url, user: result.1, pass: result.2 , postCompleted: self.checkLogin)
        }
    }
    
    func validateData() -> (result: Bool, user: String, pass: String) {
        if let user = txtUser.text {
            if !user.isEmpty {
                print("Usuario ingresado: \(user)")
                if let pass = txtPass.text {
                    if !pass.isEmpty {
                        print("Password ingresada: \(pass)")
                        return (true, user, pass)
                    } else {
                        print("Password vacia")
                         showAlert("Complete los campos obligatorios", messageToShow: "Por favor, ingrese usuario y contraseña.")
                        loginSucceeded = false
                        return (false, user, pass)
                    }
                } else {
                    return (false, "", "")
                }
            } else {
                print("Usuario vacio")
                showAlert("Complete los campos obligatorios", messageToShow: "Por favor, ingrese usuario y contraseña.")
                loginSucceeded = false
                return (false, "", "")
            }
        } else {
            return (false, "", "")
        }
    }
    
    func checkLogin(succeed: Bool, msg: String){
        if (succeed){
            loginSucceeded = true
            NSOperationQueue.mainQueue().addOperationWithBlock {
                self.performSegueWithIdentifier("goToHome", sender: self)
            }
        } else {
            loginSucceeded = false
        }
    }
    
    override func shouldPerformSegueWithIdentifier(identifier: String, sender: AnyObject?) -> Bool {
        if identifier == "goToHome" {
            if loginSucceeded {
                loginSucceeded = false
                return true
            }
        }
        return false
    }
    
    func showAlert(title: String, messageToShow: String) -> Void {
        let alert = UIAlertController(title:title, message:messageToShow, preferredStyle: .Alert) // 1
        let firstAction = UIAlertAction(title: "Aceptar", style: .Default) { (alert: UIAlertAction!) -> Void in
            NSLog("You pressed button one")
        } // 2
        
//        let action = UIAlertAction(title: "OK", style: UIAlertActionStyle.Cancel,handler: {_ in
//            toFocus.becomeFirstResponder()
        
//        let secondAction = UIAlertAction(title: "two", style: .Default) { (alert: UIAlertAction!) -> Void in
//            NSLog("You pressed button two")
//        } // 3
        
        alert.addAction(firstAction) // 4
        //alert.addAction(secondAction) // 5
        presentViewController(alert, animated: true, completion:nil) // 6
    }

}
