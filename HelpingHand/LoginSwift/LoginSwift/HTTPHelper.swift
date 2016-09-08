//
//  HTTPHelper.swift
//  LoginSwift
//
//  Created by Griselda Cuenca on 8/30/16.
//  Copyright © 2016 Griselda Cuenca. All rights reserved.
//

import Foundation
class HTTPHelper {
    
    func post(url: String, user: String, pass: String, postCompleted: (succeeded: Bool, msg: String) -> ()) {
        let jsonObj = createJsonObject(user, password: pass)
        print(jsonObj)
            
        if NSJSONSerialization.isValidJSONObject(jsonObj) {
            do {
                let jsonData = try NSJSONSerialization.dataWithJSONObject(jsonObj, options: .PrettyPrinted)
                    
                // create post request
                let requestURL: NSURL = NSURL(string: url)!
                print(url)
                let request = NSMutableURLRequest(URL: requestURL)
                request.HTTPMethod = "POST"
                
                // insert json data to the request
                request.setValue("application/json; charset=utf-8", forHTTPHeaderField: "Content-Type")
                request.HTTPBody = jsonData
                
                let task = NSURLSession.sharedSession().dataTaskWithRequest(request){ data, response, error in
                    if error != nil{
                        print("Error -> \(error)")
                        return
                    }
                    
                    do {
                        let jsonResponse = try NSJSONSerialization.JSONObjectWithData(data!, options: .AllowFragments) as? [String:AnyObject]
                        print("Result -> \(jsonResponse)")
                        if let result = jsonResponse!["result"] as? String {
                            if result == "success" {
                                postCompleted(succeeded: true, msg: "Logged in.")
                            } else {
                                postCompleted(succeeded: false, msg: "Error")
                            }
                        } else {
                            print("no llego al resultado")
                            postCompleted(succeeded: false, msg: "Invalid Json Response")
                        }
                        return
                    } catch {
                        print("error serializing JSON: \(error)")
                        postCompleted(succeeded: false, msg: "error serializing JSON")
                    }
                }
                task.resume()
            } catch {
                print("error: \(error)")
            }
        }
    }
    
    func createJsonObject(username: String, password: String) -> [String:AnyObject] {
        let jsonObject: [String:AnyObject] = [
            "operation":"login",
            "user":[
                "idUsuario":"\(username)",
                "password":"\(password)"
            ]
        ]
        return jsonObject
    }
}
