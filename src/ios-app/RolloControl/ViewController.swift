//
//  ViewController.swift
//  RolloControl
//
//  Created by Björn Bosse on 16.04.17.
//  Copyright © 2017 Björn Bosse. All rights reserved.
//

import UIKit

class ViewController: UIViewController {
    @IBOutlet weak var webView: UIWebView!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        
        let url = URL(string: "http://test.benntec-quiz-app.de/rollo/");
        let requestObj = URLRequest(url: url!,
                                    cachePolicy: NSURLRequest.CachePolicy.reloadIgnoringLocalAndRemoteCacheData,
                                    timeoutInterval: 10.0);
        
        webView.scrollView.isScrollEnabled = false;
        webView.scrollView.bounces = false;
        webView.loadRequest(requestObj);
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }

    // RETURN TRUE TO HIDE AND FALSE TO SHOW STATUS BAR
    override var prefersStatusBarHidden: Bool {
        return true
    }
}

