import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:flutter_phoenix/flutter_phoenix.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:http/http.dart';
import 'package:padmametering/Const.dart';
import 'package:padmametering/CustomerPage.dart';
import 'package:padmametering/MySharedPreferences.dart';
import 'package:padmametering/Registration.dart';

class LoginPage extends StatefulWidget {
  @override
  LoginPageState createState() => LoginPageState();
}


class LoginPageState extends State<LoginPage> {

  TextEditingController emailTECtl = TextEditingController();
  TextEditingController passwordTECtl = TextEditingController();
  BuildContext ctx;

  @override
  Widget build(BuildContext context) {
    ctx = context;
    return Scaffold(
      body: Container(
        child: Padding(
          padding: EdgeInsets.all(15.0),
          child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: <Widget>[
                Text('Email'),
                SizedBox(height: 10),
                TextField(
                    decoration: InputDecoration(
                        contentPadding:
                        EdgeInsets.fromLTRB(
                            15.0, 5.0, 5.0, 5.0),
                        border: OutlineInputBorder()),
                    controller: emailTECtl,
                    keyboardType: TextInputType.emailAddress),
                SizedBox(height: 20),
                Text('Password'),
                SizedBox(height: 10),
                TextField(
                    decoration: InputDecoration(
                        contentPadding:
                        EdgeInsets.fromLTRB(
                            15.0, 5.0, 5.0, 5.0),
                        border: OutlineInputBorder()),
                    controller: passwordTECtl,
                    keyboardType: TextInputType.visiblePassword),
                SizedBox(height: 20),
                MaterialButton(
                  child: Text('Login',style: TextStyle(fontSize: 20)),
                  minWidth: double.infinity,
                  textColor: Colors.white,
                  padding: EdgeInsets.only(top: 20, bottom: 20),
                  color: Color.fromRGBO(61, 45, 120, 1),
                  shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(7)),
                  onPressed: () {
                    checkInput(context);
                  },
                ),
                SizedBox(height: 20),
                MaterialButton(
                  child: Text('Registration',style: TextStyle(fontSize: 20)),
                  minWidth: double.infinity,
                  textColor: Colors.white,
                  padding: EdgeInsets.only(top: 20, bottom: 20),
                  color: Color.fromRGBO(61, 45, 120, 1),
                  shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(7)),
                  onPressed: () {

                    Navigator.of(context).push(
                      MaterialPageRoute(
                        builder: (context) {
                          return RegistrationPage();
                        },
                      ),
                    );

                  },
                )
              ]),
        ) /* add child content here */,
      ),
    );
  }

  void checkInput(BuildContext context) {
    if (emailTECtl.text.toString().isEmpty) {
      Fluttertoast.showToast(msg: "Email required.");
    } else if (passwordTECtl.text.toString().isEmpty) {
      Fluttertoast.showToast(msg: "Password required.");
    } else {
      showAlertDialog(context, "Please wait, registration processing...");
      onLogin(context);
    }
  }

  showAlertDialog(BuildContext context, String msg) {
    AlertDialog alert = AlertDialog(
      content: ListTile(
        leading: CircularProgressIndicator(),
        title: Text("Loading"),
        subtitle: Text(msg),
      ),
    );
    showDialog(
      barrierDismissible: false,
      context: context,
      builder: (BuildContext context) {
        return alert;
      },
    );
  }

  Future<void> onLogin(BuildContext context) async {

    String url = serverBaseUrl + '/api/users/login';
    Map<String, String> headers = {"Content-type": "application/json"};
    var request = {
      'user': {
        'email': emailTECtl.text,
        'password': passwordTECtl.text
      }
    };

    print(url);

    Response response =
        await post(url, headers: headers, body: json.encode(request));

    Navigator.of(context, rootNavigator: true).pop('dialog');

    if (response.statusCode == 200) {

      var body = json.decode(response.body);

      if(body['code'] == 200){

        var user = body['user'];
        var data = {
          'email' : user['email'],
          'sessionId' : user['sessionId']
        };

        MySharedPreferences.setStringValue('user',jsonEncode(data)).whenComplete((){

          Navigator.of(context).pushReplacement(
            MaterialPageRoute(
              builder: (context) {
                return CustomerPage(email : user['email'],sessionId : user['sessionId']);
              },
            ),
          );

        });

      }else{

        Fluttertoast.showToast(msg: body['msg']);

      }

    } else {
      Fluttertoast.showToast(msg: "Something went wrong.");
    }

  }



}