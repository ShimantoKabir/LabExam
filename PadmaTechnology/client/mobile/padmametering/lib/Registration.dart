import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:http/http.dart';
import 'package:padmametering/Const.dart';
import 'package:padmametering/LoginPage.dart';

class RegistrationPage extends StatefulWidget {
  @override
  RegistrationPageState createState() => RegistrationPageState();
}

class RegistrationPageState extends State<RegistrationPage> {
  TextEditingController emailTECtl = TextEditingController();
  TextEditingController passwordTECtl = TextEditingController();

  @override
  Widget build(BuildContext context) {
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
                            EdgeInsets.fromLTRB(15.0, 5.0, 5.0, 5.0),
                        border: OutlineInputBorder()),
                    controller: emailTECtl,
                    keyboardType: TextInputType.text),
                SizedBox(height: 20),
                Text('Password'),
                SizedBox(height: 10),
                TextField(
                    decoration: InputDecoration(
                        contentPadding:
                            EdgeInsets.fromLTRB(15.0, 5.0, 5.0, 5.0),
                        border: OutlineInputBorder()),
                    controller: passwordTECtl,
                    keyboardType: TextInputType.text),
                SizedBox(height: 20),
                MaterialButton(
                  child: Text('Submit', style: TextStyle(fontSize: 20)),
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
                  child: Text('Go To Login', style: TextStyle(fontSize: 20)),
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
                          return LoginPage();
                        },
                      ),
                    );
                  },
                ),
              ]),
        ) /* add child content here */,
      ),
    );
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

  Future<void> onSubmit(BuildContext context) async {
    String url = serverBaseUrl + '/api/users';
    Map<String, String> headers = {"Content-type": "application/json"};
    var request = {
      'user': {'email': emailTECtl.text, 'password': passwordTECtl.text}
    };
    print(url);
    Response response =
        await post(url, headers: headers, body: json.encode(request));

    Navigator.of(context, rootNavigator: true).pop('dialog');

    print(response.body);

    if (response.statusCode == 200) {
      print(response.body);
      var body = json.decode(response.body);

      emailTECtl.text = "";
      passwordTECtl.text = "";

      Fluttertoast.showToast(msg: body['msg']);
    } else {
      Fluttertoast.showToast(msg: "Something went wrong!");
    }
  }

  void checkInput(BuildContext context) {
    if (emailTECtl.text.toString().isEmpty) {
      Fluttertoast.showToast(msg: "Email required.");
    } else if (passwordTECtl.text.toString().isEmpty) {
      Fluttertoast.showToast(msg: "Password required.");
    } else {
      showAlertDialog(context, "Please wait, registration processing...");
      onSubmit(context);
    }
  }
}
