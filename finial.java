import java.util.List;
import java.util.concurrent.TimeUnit;
import org.json.JSONObject;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.ArrayList;

class getDir {
    public static void main(String[] args) throws Exception {

        String[] wordlist = new String[100];
        int count = Integer.parseInt(args[4]);
        String post = new String(args[0].toString());
        // System.out.println(post);
        JSONObject obj = new JSONObject(post);
        String destination = new String(args[2]);
        String travelMode = new String(args[3]);
        int temp = 0;
        for (int j = 1; j < count; j++) {
            wordlist[temp] = obj.getString("road[" + j + "]");
            temp = temp + 1;
        }
        String finish = "unfinish";
        for (int k = 0; k < count - 1; k++) {

            if (Check_road(wordlist[k]) == 0) {
                String word = wordlist[k];
                List<Double> way = Way(word);
                JSONObject outdata = new JSONObject();
                outdata.put("status", "finished");
                outdata.put("road", way);
                System.out.print(outdata);
                finish = new String("finished");
                break;
            }
        }
        // System.out.println("count="+count);
        if (finish == "unfinish") {
            outer: for (int k = 0; k < count - 1; k++) {
                // System.out.println(k);
                String word = wordlist[k];
                List<Double> ans = new ArrayList<>();
                List<Double> way = Way(word);
                String check = "true";
                int i = 0;
                while (check == "true") {
                    // i++;
                    // System.out.println(i);
                    if (i == 1) {
                        check = "False";
                    } else {
                        int lastIdx = way.size() - 1;
                        Double lastElement = way.get(lastIdx);
                        if (lastElement == 0.0) {
                            if (lastIdx > 7) {
                                for (int j = 0; j < 7; j++) {
                                    way.remove(lastIdx - j);
                                    // System.out.println(j);
                                }
                            } else {
                                for (int j = 0; j < 1; j++) {
                                    way.remove(lastIdx - j);
                                }
                            }
                            ans.addAll(way);
                            // System.out.println(ans);
                            lastIdx = way.size() - 1;
                            Double lon = way.get(lastIdx);
                            Double lat = way.get(lastIdx - 1);
                            TimeUnit.SECONDS.sleep(1);
                            word = request(destination, lat, lon, travelMode);
                            TimeUnit.SECONDS.sleep(1);
                            // System.out.println(word);
                            way = Way(word);
                            i++;
                        } else {
                            ans.addAll(way);
                            JSONObject outdata = new JSONObject();
                            outdata.put("status", "fix_finished");
                            outdata.put("road", ans);
                            System.out.print(outdata);
                            check = "False_ok";
                        }
                    }
                }
                if (check == "False_ok") {
                    break outer;
                } else {
                    if (k == count - 2) {
                        word = wordlist[0];
                        way = decodePoly(word);
                        JSONObject outdata = new JSONObject();
                        outdata.put("status", "false");
                        outdata.put("road", way);
                        System.out.print(outdata);
                    }
                }
            }
        }

    }

    private static HttpURLConnection connection;

    public static String request(String destination, double lati, double loni, String travelMode) {

        BufferedReader reader;
        String line;
        StringBuffer responseContent = new StringBuffer();
        try {
            URL url = new URL("https://maps.googleapis.com/maps/api/directions/json?origin=" + lati + "," + loni
                    + "&destination=" + destination + "&key=AIzaSyArsyAf1qR_KqNhx0xPvuA6BjBFgnfJtOQ" + "&mode="
                    + travelMode);
            // System.out.println(url);
            connection = (HttpURLConnection) url.openConnection();

            // Request setup
            connection.setRequestMethod("GET");
            connection.setConnectTimeout(5000);
            connection.setReadTimeout(5000);

            int status = connection.getResponseCode();
            // System.out.print(status);
            if (status > 299) {
                reader = new BufferedReader(new InputStreamReader(connection.getErrorStream()));
                while ((line = reader.readLine()) != null) {
                    responseContent.append(line);
                }
                reader.close();
                String getdata = responseContent.toString();
                return getdata;
            } else {
                reader = new BufferedReader(new InputStreamReader(connection.getInputStream(), "utf-8"));
                while ((line = reader.readLine()) != null) {
                    responseContent.append(line);
                }
                reader.close();
                // System.out.println(responseContent.toString());
                JSONObject obj = new JSONObject(responseContent.toString().trim());
                // System.out.println(obj);
                String getdata = obj.getJSONArray("routes").getJSONObject(0).getJSONObject("overview_polyline")
                        .getString("points");
                // System.out.println(getdata);
                return getdata;
            }

        } catch (MalformedURLException e) {
            e.printStackTrace();
            JSONObject outdata = new JSONObject();
            outdata.put("status", "java_false_connect");
            System.out.print(outdata);
            return "error";
        } catch (IOException e) {
            e.printStackTrace();
            JSONObject outdata = new JSONObject();
            outdata.put("status", "java_false_connect");
            System.out.print(outdata);
            return "error";
        } finally {
            connection.disconnect();
        }
    }

    public static List<Double> decodePoly(String encoded) {
        List<Double> poly = new ArrayList<>();
        int index = 0, len = encoded.length();
        int lat = 0, lng = 0;
        while (index < len) {
            int b, shift = 0, result = 0;
            do {
                b = encoded.charAt(index++) - 63;
                result |= (b & 0x1f) << shift;
                shift += 5;
            } while (b >= 0x20);
            int dlat = ((result & 1) != 0 ? ~(result >> 1) : (result >> 1));
            lat += dlat;
            shift = 0;
            result = 0;
            do {
                b = encoded.charAt(index++) - 63;
                result |= (b & 0x1f) << shift;
                shift += 5;
            } while (b >= 0x20);
            int dlng = ((result & 1) != 0 ? ~(result >> 1) : (result >> 1));
            lng += dlng;
            poly.add((double) lat / 1E5);
            poly.add((double) lng / 1E5);
        }

        return poly;

    }

    public static List<Double> file() throws Exception {
        File doc = new File("D:/java/lonlat.csv");
        try (BufferedReader obj = new BufferedReader(new FileReader(doc))) {
            String strng;
            List<Double> lon = new ArrayList<>();
            // List<Double> lat = new ArrayList<>();
            while ((strng = obj.readLine()) != null) {
                String[] token = strng.split(",");
                double lonti = Double.parseDouble(token[0]);
                double lati = Double.parseDouble(token[1]);
                lon.add((double) lonti);
                lon.add((double) lati);
            }
            return lon;
        }
    }

    public static List<Double> Way(String word) throws Exception {
        List<Double> poly = new ArrayList<>();
        List<Double> Fix = file();
        Double distance = 0.0;
        List<Double> DecodePoly = decodePoly(word);
        outer: for (int i = 0; i < DecodePoly.size() - 1; i = i + 2) {
            Double lat = DecodePoly.get(i);
            Double lon = DecodePoly.get(i + 1);
            if (i != DecodePoly.size() - 2) {
                Double lat_next = DecodePoly.get(i + 2);
                Double lon_next = DecodePoly.get(i + 3);
                double A = (lon - lon_next) / (lat - lat_next);
                if (lat - lat_next != 0) {
                    double B = -1;
                    double C = (lon - A * lat);
                    poly.add(lat);
                    poly.add(lon);
                    for (int j = 0; j < Fix.size() - 1; j = j + 2) {
                        distance = 111000
                                * (Math.abs(A * Fix.get(j + 1) + B * Fix.get(j) + C) / Math.sqrt(A * A + B * B));
                        // System.out.println(distance);
                        if (distance < 0.001) {
                            Double unfinish = 0.0;
                            poly.add(unfinish);
                            break outer;
                        }
                    }
                }
            }
        }
        return poly;
    }

    public static int Check_road(String word) throws Exception {
        List<Double> Fix = file();
        // Double fix_lat = 25.01852;
        // Double fix_lon = 121.5792;
        Double distance = 0.0;

        // System.out.println(decodePoly(word));
        List<Double> DecodePoly = decodePoly(word);
        // System.out.println("orgin");
        // System.out.println(DecodePoly);
        int step = 0;
        for (int i = 0; i < DecodePoly.size() - 1; i = i + 2) {
            Double lat = DecodePoly.get(i);
            Double lon = DecodePoly.get(i + 1);
            if (i != DecodePoly.size() - 2) {
                Double lat_next = DecodePoly.get(i + 2);
                Double lon_next = DecodePoly.get(i + 3);
                double A = (lon - lon_next) / (lat - lat_next);
                if (lat - lat_next != 0) {
                    double B = -1;
                    double C = (lon - A * lat);
                    for (int j = 0; j < Fix.size() - 1; j = j + 2) {
                        distance = 111000
                                * (Math.abs(A * Fix.get(j + 1) + B * Fix.get(j) + C) / Math.sqrt(A * A + B * B));
                        // System.out.println(distance);
                        if (distance < 0.001) {
                            System.out.println((Fix.get(j + 1)));
                            System.out.println(Fix.get(j));
                            step += 1;
                        }
                    }

                }
            }
        }
        return step;

    }
}
