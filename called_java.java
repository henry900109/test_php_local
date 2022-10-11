import org.json.JSONObject;
class XYZ {
     public static void main(String[] args) {
        System.out.println("hello");
        System.out.println(args[0]);
        JSONObject obj = new JSONObject();
        obj.put("int",2);
        System.out.println(obj);
    }
}