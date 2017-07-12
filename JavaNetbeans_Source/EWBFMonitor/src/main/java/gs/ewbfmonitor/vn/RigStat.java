/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package gs.ewbfmonitor.vn;

/**
 *
 * @author TIEN
 */
public class RigStat {
    public String method;
    public String error;
    public Long start_time;
    public String current_server;
    public int available_servers;
    public int server_status;
    public GPUStat[] result;
    public String getSummary(){
        String s = "Temp/Sol: ";
        for (int i = 0; i < result.length; i++){
            s += result[i].temperature + "*C/" + result[i].speed_sps + "SPS ";
        }
        return s;
    }
}
