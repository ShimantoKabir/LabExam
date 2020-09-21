import 'package:shared_preferences/shared_preferences.dart';

class MySharedPreferences{

  static SharedPreferences preferences;

  static Future<String> getStringValue(String key) async {
    preferences = await SharedPreferences.getInstance();
    return preferences.getString(key);
  }

  static Future<bool> setStringValue(String key,String val) async {
    preferences = await SharedPreferences.getInstance();
    return preferences.setString(key,val);
  }

}