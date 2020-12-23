

Stop screen from dimming by enforcing wake lock
<?php

import android.app.Activity;
import android.content.Context;
import android.os.Bundle;
import android.os.PowerManager;

public class DoNotDimScreen extends Activity  {
        
        private PowerManager.WakeLock wl;
        
        @Override
        protected void onCreate(Bundle savedInstanceState) {
                PowerManager pm = (PowerManager) getSystemService(Context.POWER_SERVICE);
                wl = pm.newWakeLock(PowerManager.FULL_WAKE_LOCK, "DoNotDimScreen");
        }
                
        @Override
        protected void onPause() {
                super.onPause();
                wl.release();
        }

        @Override
        protected void onResume() {
                super.onResume();
                wl.acquire();
        }
}

You will need to include this permission in your manifest:
<uses-permission android:name="android.permission.WAKE_LOCK" /> 
?>



Double back press to exit
<?php

private static long back_pressed;

@Override
public void onBackPressed()
{
        if (back_pressed + 2000 > System.currentTimeMillis()) super.onBackPressed();
        else Toast.makeText(getBaseContext(), "Press once again to exit!", Toast.LENGTH_SHORT).show();
        back_pressed = System.currentTimeMillis();
}
?>



Check if internet is available
<?php
/*
* @return boolean return true if the application can access the internet
*/
private boolean haveInternet(){
        NetworkInfo info = ((ConnectivityManager)getSystemService(Context.CONNECTIVITY_SERVICE)).getActiveNetworkInfo();
        if (info==null || !info.isConnected()) {
                return false;
        }
        if (info.isRoaming()) {
                // here is the roaming option you can change it if you want to disable internet while roaming, just return false
                return true;
        }
        return true;
}
?>



Scan for Wireless Networks

<?php
package com.android.wifitester;

import java.util.List;
import android.app.Activity;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.net.wifi.ScanResult;
import android.net.wifi.WifiManager;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.TextView;

public class WifiTester extends Activity {
    TextView mainText;
    WifiManager mainWifi;
    WifiReceiver receiverWifi;
    List<ScanResult> wifiList;
    StringBuilder sb = new StringBuilder();
    
    public void onCreate(Bundle savedInstanceState) {
       super.onCreate(savedInstanceState);
       setContentView(R.layout.main);
       mainText = (TextView) findViewById(R.id.mainText);
       mainWifi = (WifiManager) getSystemService(Context.WIFI_SERVICE);
       receiverWifi = new WifiReceiver();
       registerReceiver(receiverWifi, new IntentFilter(WifiManager.SCAN_RESULTS_AVAILABLE_ACTION));
       mainWifi.startScan();
       mainText.setText("\\nStarting Scan...\\n");
    }

    public boolean onCreateOptionsMenu(Menu menu) {
        menu.add(0, 0, 0, "Refresh");
        return super.onCreateOptionsMenu(menu);
    }

    public boolean onMenuItemSelected(int featureId, MenuItem item) {
        mainWifi.startScan();
        mainText.setText("Starting Scan");
        return super.onMenuItemSelected(featureId, item);
    }

    protected void onPause() {
        unregisterReceiver(receiverWifi);
        super.onPause();
    }

    protected void onResume() {
        registerReceiver(receiverWifi, new IntentFilter(WifiManager.SCAN_RESULTS_AVAILABLE_ACTION));
        super.onResume();
    }
    
    class WifiReceiver extends BroadcastReceiver {
        public void onReceive(Context c, Intent intent) {
            sb = new StringBuilder();
            wifiList = mainWifi.getScanResults();
            for(int i = 0; i < wifiList.size(); i++){
                sb.append(new Integer(i+1).toString() + ".");
                sb.append((wifiList.get(i)).toString());
                sb.append("\\n");
            }
            mainText.setText(sb);
        }
    }
}


?>



 
Open file with default application using Intents
<?php

Intent intent = new Intent();
intent.setAction(android.content.Intent.ACTION_VIEW);
File file = new File("/sdcard/test.mp4");
intent.setDataAndType(Uri.fromFile(file), "video/*");
startActivity(intent); 

Intent intent = new Intent();
intent.setAction(android.content.Intent.ACTION_VIEW);
File file = new File("/sdcard/test.mp3");
intent.setDataAndType(Uri.fromFile(file), "audio/*");
startActivity(intent); 


?>




Show an image file on the SD card in a view
<?php

public static void ShowPicture(String fileName, ImageView pic) { 
    File f = new File(Environment.getExternalStorageDirectory(), fileName); 
    FileInputStream is = null; 
    try { 
        is = new FileInputStream(f); 
    } catch (FileNotFoundException e) {
        Log.d("error: ",String.format( "ShowPicture.java file[%s]Not Found",fileName)); 
        return; 
    } 
    
    Bitmap = BitmapFactory.decodeStream(is, null, null); 
    pic.setImageBitmap(bm); 
} 


?>




Enable / disable orientation in activity

<?php

public void setOrientation(boolean status) {
                if (status) {
                        setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_LOCKED);
                } else {
                        setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_USER);
                }
        }
		
		
		or
		
		<activity android:name=".MyActivity"
		android:label="@string/app_name" 
		android:screenOrientation="portrait">

?>


Playing sound Effect using MediaPlayer
<?php
MediaPlayer mp = MediaPlayer.create(context, R.raw.sound_file_1);
mp.start();


?>



Get installed Applications with Name, Package Name, Version and Icon
<?php

class PInfo {
    private String appname = "";
    private String pname = "";
    private String versionName = "";
    private int versionCode = 0;
    private Drawable icon;
    private void prettyPrint() {
        Log.v(appname + "\t" + pname + "\t" + versionName + "\t" + versionCode);
    }
}

private ArrayList<PInfo> getPackages() {
    ArrayList<PInfo> apps = getInstalledApps(false); /* false = no system packages */
    final int max = apps.size();
    for (int i=0; i<max; i++) {
        apps.get(i).prettyPrint();
    }
    return apps;
}

private ArrayList<PInfo> getInstalledApps(boolean getSysPackages) {
    ArrayList<PInfo> res = new ArrayList<PInfo>();        
    List<PackageInfo> packs = getPackageManager().getInstalledPackages(0);
    for(int i=0;i<packs.size();i++) {
        PackageInfo p = packs.get(i);
        if ((!getSysPackages) && (p.versionName == null)) {
            continue ;
        }
        PInfo newInfo = new PInfo();
        newInfo.appname = p.applicationInfo.loadLabel(getPackageManager()).toString();
        newInfo.pname = p.packageName;
        newInfo.versionName = p.versionName;
        newInfo.versionCode = p.versionCode;
        newInfo.icon = p.applicationInfo.loadIcon(getPackageManager());
        res.add(newInfo);
    }
    return res; 
}

?>


 
Get the phone last known location using LocationManager
<?php

private double[] getGPS() {
        LocationManager lm = (LocationManager) getSystemService(Context.LOCATION_SERVICE);  
        List<String> providers = lm.getProviders(true);

        /* Loop over the array backwards, and if you get an accurate location, then break out the loop*/
        Location l = null;
        
        for (int i=providers.size()-1; i>=0; i--) {
                l = lm.getLastKnownLocation(providers.get(i));
                if (l != null) break;
        }
        
        double[] gps = new double[2];
        if (l != null) {
                gps[0] = l.getLatitude();
                gps[1] = l.getLongitude();
        }
        return gps;
}

?>


Clickable ListView Items
<?php


import java.util.ArrayList;
import java.util.List;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;

/**
 * This list adapter is derived from the "Efficient List Adapter"-Example of
 * API-Demos. It uses holder object to access the list items efficiently.
 * Additionally, click listeners are provided, which can be connected to the
 * arbitrary view items, e.g. customized checkboxes, or other clickable
 * Image/TextViews. Implement subclasses of them and add your listeners to your
 * "clickable" views.
 * 
 * @author poss3x
 */
public abstract class ClickableListAdapter extends BaseAdapter {
        private LayoutInflater mInflater;
        private List mDataObjects; // our generic object list
        private int mViewId;

        /**
         * This is the holder will provide fast access to arbitrary objects and
         * views. Use a subclass to adapt it for your personal needs.
         */
        public static class ViewHolder {
                // back reference to our list object
                public Object data;
        }

        /**
         * The click listener base class.
         */
        public static abstract class OnClickListener implements
                        View.OnClickListener {

                private ViewHolder mViewHolder;

                /**
                 * @param holder The holder of the clickable item
                 */
                public OnClickListener(ViewHolder holder) {
                        mViewHolder = holder;
                }

                // delegates the click event
                public void onClick(View v) {
                        onClick(v, mViewHolder);
                }

                /**
                 * Implement your click behavior here
                 * @param v  The clicked view.
                 */
                public abstract void onClick(View v, ViewHolder viewHolder);
        };

        /**
         * The long click listener base class.
         */
        public static abstract class OnLongClickListener implements
                        View.OnLongClickListener {
                private ViewHolder mViewHolder;

                /**
                 * @param holder The holder of the clickable item
                 */
                public OnLongClickListener(ViewHolder holder) {
                        mViewHolder = holder;
                }

                // delegates the click event
                public boolean onLongClick(View v) {
                        onLongClick(v, mViewHolder);
                        return true;
                }

                /**
                 * Implement your click behavior here
                 * @param v  The clicked view.
                 */
                public abstract void onLongClick(View v, ViewHolder viewHolder);

        };

        /**
         * @param context The current context
         * @param viewid The resource id of your list view item
         * @param objects The object list, or null, if you like to indicate an empty
         * list
         */
        public ClickableListAdapter(Context context, int viewid, List objects) {

                // Cache the LayoutInflate to avoid asking for a new one each time.
                mInflater = LayoutInflater.from(context);
                mDataObjects = objects;
                mViewId = viewid;

                if (objects == null) {
                        mDataObjects = new ArrayList<Object>();
                }
        }

        /**
         * The number of objects in the list.
         */
        public int getCount() {
                return mDataObjects.size();
        }

        /**
         * @return We simply return the object at position of our object list Note,
         *         the holder object uses a back reference to its related data
         *         object. So, the user usually should use {@link ViewHolder#data}
         *         for faster access.
         */
        public Object getItem(int position) {
                return mDataObjects.get(position);
        }

        /**
         * We use the array index as a unique id. That is, position equals id.
         * 
         * @return The id of the object
         */
        public long getItemId(int position) {
                return position;
        }

        /**
         * Make a view to hold each row. This method is instantiated for each list
         * object. Using the Holder Pattern, avoids the unnecessary
         * findViewById()-calls.
         */
        public View getView(int position, View view, ViewGroup parent) {
                // A ViewHolder keeps references to children views to avoid uneccessary
                // calls
                // to findViewById() on each row.
                ViewHolder holder;

                // When view is not null, we can reuse it directly, there is no need
                // to reinflate it. We only inflate a new View when the view supplied
                // by ListView is null.
                if (view == null) {

                        view = mInflater.inflate(mViewId, null);
                        // call the user's implementation
                        holder = createHolder(view);
                        // we set the holder as tag
                        view.setTag(holder);

                } else {
                        // get holder back...much faster than inflate
                        holder = (ViewHolder) view.getTag();
                }

                // we must update the object's reference
                holder.data = getItem(position);
                // call the user's implementation
                bindHolder(holder);

                return view;
        }

        /**
         * Creates your custom holder, that carries reference for e.g. ImageView
         * and/or TextView. If necessary connect your clickable View object with the
         * PrivateOnClickListener, or PrivateOnLongClickListener
         * 
         * @param vThe view for the new holder object
         */
        protected abstract ViewHolder createHolder(View v);

        /**
         * Binds the data from user's object to the holder
         * @param h  The holder that shall represent the data object.
         */
        protected abstract void bindHolder(ViewHolder h);
}


// -------------------------------------------------------------
//                      E X A M P L E
// -------------------------------------------------------------

// LAYOUT FILE

<?xml version="1.0" encoding="utf-8"?>
<LinearLayout
  xmlns:android="http://schemas.android.com/apk/res/android"
  android:layout_width="fill_parent"
  android:layout_height="wrap_content"
  android:orientation="horizontal"
  android:gravity="center_vertical"
  >
  
<TextView android:text="Text" android:id="@+id/listitem_text"
                        android:layout_weight="1" 
                        android:layout_width="fill_parent" 
                        android:layout_height="wrap_content"
                        ></TextView>
<ImageView android:id="@+id/listitem_icon"
                        android:src="@drawable/globe2_32x32"
                        android:layout_width="wrap_content" 
                        android:layout_height="wrap_content"
                        android:maxWidth="32px"
                        android:maxHeight="32px"
                        >
</ImageView>
</LinearLayout>



//--------------------------------------------------------------
// ACTIVITY
//--------------------------------------------------------------
package com.o1.android.view;
import java.util.ArrayList;
import java.util.List;
import android.app.ListActivity;
import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;
import com.o1.android.view.ClickableListAdapter;
import com.o1.android.view.ClickableListAdapter.ViewHolder;

/**
 * An example how to implement the ClickableListAdapter for a list view layout containing
 * a TextView and an ImageView.
 * @author poss3x
 */
public class ClickableListItemActivity extends ListActivity {

        /**
         * Our data class. This data will be bound to 
         * MyViewHolder, which in turn is used for the 
         * ListView. 
         */
        static class MyData {
                public MyData(String t, boolean e) {
                        text = t;
                        enable = e;
                }

                String text;
                boolean enable;
        }
        
        /**
         * Our very own holder referencing the view elements
         * of our ListView layout
         */
        static class MyViewHolder extends ViewHolder {

                public MyViewHolder(TextView t, ImageView i) {
                        text = t;
                        icon = i;
                }
                TextView text;
                ImageView icon;
        }

        /**
         * The implementation of ClickableListAdapter
         */
        private class MyClickableListAdapter extends ClickableListAdapter {
                public MyClickableListAdapter(Context context, int viewid,
                                List<MyData> objects) {
                        super(context, viewid, objects);
                        // nothing to do
                }

                protected void bindHolder(ViewHolder h) {
                        // Binding the holder keeps our data up to date.
                        // In contrast to createHolder this method is called for all items
                        // So, be aware when doing a lot of heavy stuff here.
                        // we simply transfer our object's data to the list item representatives
                        MyViewHolder mvh = (MyViewHolder) h;
                        MyData mo = (MyData)mvh.data; 
                        mvh.icon.setImageBitmap(
                                        mo.enable ? ClickableListItemActivity.this.mIconEnabled
                                                        : ClickableListItemActivity.this.mIconDisabled);
                        mvh.text.setText(mo.text);

                }

                @Override
                protected ViewHolder createHolder(View v) {
                        // createHolder will be called only as long, as the ListView is not filled
                        // entirely. That is, where we gain our performance:
                        // We use the relatively costly findViewById() methods and
                        // bind the view's reference to the holder objects.
                        TextView text = (TextView) v.findViewById(R.id.listitem_text);
                        ImageView icon = (ImageView) v.findViewById(R.id.listitem_icon);
                        ViewHolder mvh = new MyViewHolder(text, icon);

                        // Additionally, we make some icons clickable
                        // Mind, that item becomes clickable, when adding a click listener (see API)
                        // so, it is not necessary to use the android:clickable attribute in XML
                        icon.setOnClickListener(new ClickableListAdapter.OnClickListener(mvh) {

                                public void onClick(View v, ViewHolder viewHolder) {
                                        // we toggle the enabled state and also switch the icon
                                        MyViewHolder mvh = (MyViewHolder) viewHolder;
                                        MyData mo = (MyData) mvh.data;
                                        mo.enable = !mo.enable; // toggle
                                        ImageView icon = (ImageView) v;
                                        icon.setImageBitmap(
                                                        mo.enable ? ClickableListItemActivity.this.mIconEnabled
                                                                        : ClickableListItemActivity.this.mIconDisabled);
                                }
                        });

                        // for text we implement a long click listener
                        text.setOnLongClickListener(new ClickableListAdapter.OnLongClickListener(mvh) {

                                @Override
                                public void onLongClick(View v, ViewHolder viewHolder) {
                                        
                                        MyViewHolder mvh = (MyViewHolder) viewHolder;
                                        MyData mo = (MyData)mvh.data;
                                        
                                        // we toggle an '*' in our text element
                                        String s = mo.text;
                                        if (s.charAt(0) == '*') {
                                                mo.text = s.substring(1);
                                        } else {
                                                mo.text = '*' + s;
                                        }
                                        mvh.text.setText(mo.text);
                                }

                        });

                        return mvh; // finally, we return our new holder
                }

        }

                
        @Override
        public void onCreate(Bundle savedInstanceState) {
                super.onCreate(savedInstanceState);

                // preloading our icons
                mIconEnabled = BitmapFactory.decodeResource(this.getResources(),
                                R.drawable.globe2_32x32);
                mIconDisabled = BitmapFactory.decodeResource(this.getResources(),
                                R.drawable.globe2_32x32_trans);
                
                // fill list with some items...
                // to demonstrate the performance we create a bunch of data objects
                for (int i = 0; i < 250; ++i) {
                        mObjectList.add(new MyData("Some Text " + i, true));
                }
                //here we set our adapter
                setListAdapter(new MyClickableListAdapter(this,
                                R.layout.clickablelistitemview, mObjectList));

        }

        // --------------- field section -----------------
        private Bitmap mIconEnabled;
        private Bitmap mIconDisabled;

        private List<MyData> mObjectList = new ArrayList<MyData>();

}
}
?>


Simple Alert Dialog Popup with Title, Message, Icon and Button

<?php

AlertDialog alertDialog = new AlertDialog.Builder(this).create();
alertDialog.setTitle("Title");
alertDialog.setMessage("Message");
alertDialog.setButton("OK", new DialogInterface.OnClickListener() {
   public void onClick(DialogInterface dialog, int which) {
      // TODO Add your code for the button here.
   }
});
// Set the Icon for the Dialog
alertDialog.setIcon(R.drawable.icon);
alertDialog.show();

?>


Get the content from a HttpResponse (or any InputStream) as a String
<?php

// Fast Implementation
private StringBuilder inputStreamToString(InputStream is) {
    String line = "";
    StringBuilder total = new StringBuilder();
    
    // Wrap a BufferedReader around the InputStream
    BufferedReader rd = new BufferedReader(new InputStreamReader(is));

    // Read response until the end
    while ((line = rd.readLine()) != null) { 
        total.append(line); 
    }
    
    // Return full string
    return total;
}

// Slow Implementation
private String inputStreamToString(InputStream is) {
    String s = "";
    String line = "";
    
    // Wrap a BufferedReader around the InputStream
    BufferedReader rd = new BufferedReader(new InputStreamReader(is));
    
    // Read response until the end
    while ((line = rd.readLine()) != null) { s += line; }
    
    // Return full string
    return s;
}

?>


open browser to web page URL via intent
<?php

Intent viewIntent = new Intent("android.intent.action.VIEW", Uri.parse("http://www.novoda.com"));
startActivity(viewIntent); 

?>



Get My Phone Number
<?php
    private String getMyPhoneNumber(){
        TelephonyManager mTelephonyMgr;
        mTelephonyMgr = (TelephonyManager)
                getSystemService(Context.TELEPHONY_SERVICE); 
        return mTelephonyMgr.getLine1Number();
        }

        private String getMy10DigitPhoneNumber(){
                String s = getMyPhoneNumber();
                return s.substring(2);
        }


?>


Kill background processes to free up memory
<?php

ActivityManager activityManager = (ActivityManager)  getActivity().getSystemService(Context.ACTIVITY_SERVICE);
List<ActivityManager.RunningAppProcessInfo> runAppList = activityManager.getRunningAppProcesses();
int listsize = runAppList.size();
                
if (runAppList != null) {
                for (int i = 0; i < listsize; ++i) {
                        if (runAppList.get(i).pid != android.os.Process.myPid()) {
                                android.os.Process.killProcess(runAppList.get(i).pid);
                                activityManager.killBackgroundProcesses(runAppList.get(i).processName);
                        }
                }
}

?>



List all music files
<?php


//Some audio may be explicitly marked as not being music
String selection = MediaStore.Audio.Media.IS_MUSIC + " != 0";

String[] projection = {
        MediaStore.Audio.Media._ID,
        MediaStore.Audio.Media.ARTIST,
        MediaStore.Audio.Media.TITLE,
        MediaStore.Audio.Media.DATA,
        MediaStore.Audio.Media.DISPLAY_NAME,
        MediaStore.Audio.Media.DURATION
};

cursor = this.managedQuery(
        MediaStore.Audio.Media.EXTERNAL_CONTENT_URI,
        projection,
        selection,
        null,
        null);

private List<String> songs = new ArrayList<String>();
while(cursor.moveToNext()){
        songs.add(cursor.getString(0) + "||" + cursor.getString(1) + "||" +   cursor.getString(2) + "||" +   cursor.getString(3) + "||" +  cursor.getString(4) + "||" +  cursor.getString(5));
}
?>

 

Prompt User Input with an AlertDialog
<?php

AlertDialog.Builder alert = new AlertDialog.Builder(this);

alert.setTitle("Title");
alert.setMessage("Message");

// Set an EditText view to get user input 
final EditText input = new EditText(this);
alert.setView(input);

alert.setPositiveButton("Ok", new DialogInterface.OnClickListener() {
public void onClick(DialogInterface dialog, int whichButton) {
  String value = input.getText();
  // Do something with value!
  }
});

alert.setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
  public void onClick(DialogInterface dialog, int whichButton) {
    // Canceled.
  }
});

alert.show();

?>



Send a notification
<?php

 public void showNotification(Context context, String title, String body, Intent intent) {
        NotificationManager notificationManager = (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);

        int notificationId = 1;
        String channelId = "channel-01";
        String channelName = "Channel Name";
        int importance = NotificationManager.IMPORTANCE_HIGH;

        if (android.os.Build.VERSION.SDK_INT >= android.os.Build.VERSION_CODES.O) {
            NotificationChannel mChannel = new NotificationChannel(
                    channelId, channelName, importance);
            notificationManager.createNotificationChannel(mChannel);
        }

        NotificationCompat.Builder mBuilder = new NotificationCompat.Builder(context, channelId)
                .setSmallIcon(R.mipmap.ic_launcher)
                .setContentTitle(title)
                .setContentText(body)
                .setStyle(new NotificationCompat.BigTextStyle().bigText("jhkhkhkjhjjkhkjhjkjhjk"))
                .setColor(ContextCompat.getColor(context, R.color.colorPrimaryDark))
                .setVibrate(new long[]{1000, 1000})
                .setSound(Settings.System.DEFAULT_NOTIFICATION_URI)
                 .setPriority(Notification.PRIORITY_HIGH)
                .setAutoCancel(true);

        TaskStackBuilder stackBuilder = TaskStackBuilder.create(context);
        stackBuilder.addNextIntent(intent);
        PendingIntent resultPendingIntent = stackBuilder.getPendingIntent(
                0,
                PendingIntent.FLAG_UPDATE_CURRENT
        );
        mBuilder.setContentIntent(resultPendingIntent);

        notificationManager.notify(notificationId, mBuilder.build());
    }
	
	
	public static void removeNotification(Context context){
    NotificationManager notificationManager =
            (NotificationManager) context.getSystemService(NOTIFICATION_SERVICE);
    notificationManager.cancelAll();
}
	
	
	          .setContentTitle(title)
                .setContentText(messageBody)
                .setStyle(new NotificationCompat.BigTextStyle().bigText(messageBody))
                /*.setLargeIcon(largeIcon)*/
                .setSmallIcon(R.drawable.app_logo_color) //needs white icon with transparent BG (For all platforms)
                .setColor(ContextCompat.getColor(context, R.color.colorPrimaryDark))
                .setVibrate(new long[]{1000, 1000})
                .setSound(Settings.System.DEFAULT_NOTIFICATION_URI)
                .setContentIntent(pendingIntent)
                .setPriority(Notification.PRIORITY_HIGH)
                .setAutoCancel(true)
				                .setOngoing(true)


?>


Stop screen from dimming by enforcing wake lock
<?php

import android.app.Activity;
import android.content.Context;
import android.os.Bundle;
import android.os.PowerManager;

public class DoNotDimScreen extends Activity  {
        
        private PowerManager.WakeLock wl;
        
        @Override
        protected void onCreate(Bundle savedInstanceState) {
                PowerManager pm = (PowerManager) getSystemService(Context.POWER_SERVICE);
                wl = pm.newWakeLock(PowerManager.FULL_WAKE_LOCK, "DoNotDimScreen");
        }
                
        @Override
        protected void onPause() {
                super.onPause();
                wl.release();
        }

        @Override
        protected void onResume() {
                super.onResume();
                wl.acquire();
        }
}

You will need to include this permission in your manifest:
<uses-permission android:name="android.permission.WAKE_LOCK" /> 

?>


 
Simple FragmentActivity
<?php

//mainactivity.java

public class MainActivity extends FragmentActivity implements UiListener{
        private MainFragment fragment;
        @Override
        protected void onCreate(Bundle arg0) {
                super.onCreate(arg0);
                setContentView(R.layout.activity_main);
                fragment = (MainFragment) getSupportFragmentManager().findFragmentById(R.id.main_fragment);
        }
        public void onButtonClicked(){
              // handle button clicked
        }
}

//layout/activity_main.xml
/*
<?xml version="1.0" encoding="utf-8"?>
<RelativeLayout xmlns:android="http://schemas.android.com/apk/res/android"
    android:layout_width="match_parent"
    android:layout_height="match_parent" >
    <fragment android:name="MainFragment"
        android:id="@+id/main_fragment"
        android:layout_width="match_parent"
        android:layout_height="match_parent" />
</RelativeLayout>
*/

//mainfragment.java

public class MainFragment extends Fragment {

        public interface UiListener{
                public void onButtonClicked();
        }
        
        private UiListener uiCallback;
        
        @Override
        public void onAttach(Activity activity) {               
                super.onAttach(activity);               
                try{
                        uiCallback = (UiListener) activity; // check if the interface is implemented
                }catch(ClassCastException e){
                        e.printStackTrace();
                }
        }
        
        @Override
        public View onCreateView(LayoutInflater inflater, ViewGroup container,
                        Bundle savedInstanceState) {
                return inflater.inflate(R.layout.fragment_main, container, false);
        }
        
        @Override
        public void onViewCreated(View view, Bundle savedInstanceState) {
                super.onViewCreated(view, savedInstanceState);
                                
                view.findViewById(R.id.button).setOnClickListener(new OnClickListener() {                       
                        @Override
                        public void onClick(View v) {
                                onButtonClicked();
                        }
                });
    }
}

//layout/fragment_main.xml
/*
<?xml version="1.0" encoding="utf-8"?>
<RelativeLayout xmlns:android="http://schemas.android.com/apk/res/android"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:padding="5dp" >    
    <Button
        android:id="@+id/button"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:text="Click me!" />
</RelativeLayout>
*/

?>


Retrieve JSON from a REST web service
<?php

      String result = queryRESTurl("http://location/of/wellformed/json.json");

        try{
                JSONObject json = new JSONObject(result);
                JSONArray nameArray = json.names();
                JSONArray valArray = json.toJSONArray(nameArray);
                for (int i = 0; i < valArray.length(); i++) {
                        Log.i(TAG, "<jsonname" + i + ">\\n" + nameArray.getString(i)    + "\\n</jsonname" + i + ">\\n" + "<jsonvalue" + i + ">\\n" + valArray.getString(i) + "\\n</jsonvalue"   + i + ">");
                }
        }
        catch (JSONException e) {
                Log.e("JSON", "There was an error parsing the JSON", e);
        }



public String queryRESTurl(String url) {
        HttpClient httpclient = new DefaultHttpClient();
        HttpGet httpget = new HttpGet(url);
        HttpResponse response;
        
        try {
                response = httpclient.execute(httpget);
                Log.i(TAG, "Status:[" + response.getStatusLine().toString() + "]");
                HttpEntity entity = response.getEntity();
                
                if (entity != null) {
                        
                        InputStream instream = entity.getContent();
                        String result = RestClient.convertStreamToString(instream);
                        Log.i(TAG, "Result of converstion: [" + result + "]");
                        
                        instream.close();
                        return result;
                }
        } catch (ClientProtocolException e) {
                Log.e("REST", "There was a protocol based error", e);
        } catch (IOException e) {
                Log.e("REST", "There was an IO Stream related error", e);
        }
        
        return null;
}

?>


Disable Screenshot in Android
<?php

getWindow().setFlags(WindowManager.LayoutParams.FLAG_SECURE,WindowManager.LayoutParams.FLAG_SECURE);


?>

Calculate distance between two GPS coordinates
<?php

// If you have just the coordinates:
Location.distanceBetween(double startLatitude, double startLongitude, double endLatitude, double endLongitude, float[] results)

// If you have two Location objects:
float distance_meters = Location1.distanceTo(Location2)

// This is an approximation function, which does not take the 
private double gps2m(float lat_a, float lng_a, float lat_b, float lng_b) {
    float pk = (float) (180/3.14169);
    float a1 = lat_a / pk;
    float a2 = lng_a / pk;
    float b1 = lat_b / pk;
    float b2 = lng_b / pk;

    float t1 = FloatMath.cos(a1) * FloatMath.cos(a2) * FloatMath.cos(b1) * FloatMath.cos(b2);
    float t2 = FloatMath.cos(a1) * FloatMath.sin(a2) * FloatMath.cos(b1) * FloatMath.sin(b2);
    float t3 = FloatMath.sin(a1) * FloatMath.sin(b1);
    double tt = Math.acos(t1 + t2 + t3);

    return 6366000*tt;
}

?>


Navigation  Item
<?php
main activity

 private TextView mTextMessage;

    private BottomNavigationView.OnNavigationItemSelectedListener mOnNavigationItemSelectedListener
            = new BottomNavigationView.OnNavigationItemSelectedListener() {

        @Override
        public boolean onNavigationItemSelected(@NonNull MenuItem item) {
            switch (item.getItemId()) {
                case R.id.navigation_home:
                    mTextMessage.setText(R.string.title_home);
                    return true;
                case R.id.navigation_dashboard:
                    mTextMessage.setText(R.string.title_dashboard);
                    return true;
                case R.id.navigation_notifications:
                    mTextMessage.setText(R.string.title_notifications);
                    return true;
            }
            return false;
        }
    };
	
	on cearte
	  BottomNavigationView navView = findViewById(R.id.nav_view);
        mTextMessage = findViewById(R.id.message);
        navView.setOnNavigationItemSelectedListener(mOnNavigationItemSelectedListener);

 <com.google.android.material.bottomnavigation.BottomNavigationView
        android:id="@+id/nav_view"
        android:layout_width="0dp"
        android:layout_height="wrap_content"
        android:layout_marginStart="0dp"
        android:layout_marginEnd="0dp"
        android:background="?android:attr/windowBackground"
        app:layout_constraintBottom_toBottomOf="parent"
        app:layout_constraintLeft_toLeftOf="parent"
        app:layout_constraintRight_toRightOf="parent"
        app:menu="@menu/bottom_nav_menu" />
		
		menu >><<<<<<<<
		
 <menu xmlns:android="http://schemas.android.com/apk/res/android">

    <item
        android:id="@+id/navigation_home"
        android:icon="@drawable/ic_home_black_24dp"
        android:title="@string/title_home" />

    <item
        android:id="@+id/navigation_dashboard"
        android:icon="@drawable/ic_dashboard_black_24dp"
        android:title="@string/title_dashboard" />

    <item
        android:id="@+id/navigation_notifications"
        android:icon="@drawable/ic_notifications_black_24dp"
        android:title="@string/title_notifications" />

    <item
        android:id="@+id/notifications"
        android:icon="@drawable/ic_notifications_black_24dp"
        android:title="@string/title_notifications" />

    <item
        android:id="@+id/navigation"
        android:icon="@drawable/ic_home_black_24dp"
        android:title="@string/title_notifications" />
		
		
		gradel
		
		 implementation 'androidx.appcompat:appcompat:1.0.2'
    implementation 'com.google.android.material:material:1.0.0'
    implementation 'androidx.constraintlayout:constraintlayout:1.1.3'
    implementation 'androidx.vectordrawable:vectordrawable:1.0.1'

</menu>
?>


fad
<?php

        FloatingActionButton fab = (FloatingActionButton) findViewById(R.id.fab);
        fab.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Snackbar.make(view, "Replace with your own action", Snackbar.LENGTH_LONG)
                        .setAction("Action", null).show();
            }
        });

 <com.google.android.material.floatingactionbutton.FloatingActionButton
        android:id="@+id/fab"
        android:layout_width="166dp"
        android:layout_height="wrap_content"
        android:layout_gravity="bottom|end"
        android:layout_margin="@dimen/fab_margin"
        app:srcCompat="@android:drawable/ic_dialog_email" />


  implementation 'androidx.appcompat:appcompat:1.0.2'
    implementation 'androidx.legacy:legacy-support-v4:1.0.0'
    implementation 'androidx.recyclerview:recyclerview:1.0.0'
    implementation 'com.google.android.material:material:1.0.0'
	
?>



move between activity
<?php

	Intent nextScreen = new Intent(getApplicationContext(), SecondScreenActivity.class);
				
				//Sending data to another Activity
				nextScreen.putExtra("name", inputName.getText().toString());
				nextScreen.putExtra("email", inputEmail.getText().toString());
				
				// starting new activity
				startActivity(nextScreen);
				------------------------------------
		Button btnClose = (Button) findViewById(R.id.btnClose);
        
        Intent i = getIntent();
        // Receiving the Data
        String name = i.getStringExtra("name");
        String email = i.getStringExtra("email");
        
        // Displaying Received data
        txtName.setText(name);
        txtEmail.setText(email);
        
        // Binding Click event to Button
        btnClose.setOnClickListener(new View.OnClickListener() {
			
			public void onClick(View arg0) {
				//Closing SecondScreen Activity
				finish();
			}
		});

?>


flash light
<?php
 
 on
if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
    CameraManager camManager = (CameraManager) getSystemService(Context.CAMERA_SERVICE);
    String cameraId = null; // Usually back camera is at 0 position.
    try {
        cameraId = camManager.getCameraIdList()[0];
        camManager.setTorchMode(cameraId, true);   //Turn ON
    } catch (CameraAccessException e) {
        e.printStackTrace();
    }
}

off

camManager.setTorchMode(cameraId, false);


menfifst
<uses-permission android:name="android.permission.CAMERA"/>
<uses-permission android:name="android.permission.FLASHLIGHT"/>

?>


take a pic
<?php


    public void captureImage(View view) {
        Intent camera_intent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
        startActivityForResult(camera_intent, CAMERA_REQUEST);

    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {

        imgView = (ImageView) findViewById(R.id.imageView1);
        imgView.setScaleType(ImageView.ScaleType.CENTER_CROP);

        super.onActivityResult(requestCode, resultCode, data);
        switch(requestCode){
            case CAMERA_REQUEST:
                if(resultCode==RESULT_OK){
                    Bitmap thumbnail = (Bitmap) data.getExtras().get("data");
                    imgView.setImageBitmap(thumbnail);
                }
        }
    }


?>


run time permission
<?php

 int MyVersion = Build.VERSION.SDK_INT;
        if (MyVersion > Build.VERSION_CODES.LOLLIPOP_MR1) {
            if (!checkIfAlreadyhavePermission()) {
                requestForSpecificPermission();
            }
        }
		
		
		
		
		
    private boolean checkIfAlreadyhavePermission() {
        int result = ContextCompat.checkSelfPermission(this, Manifest.permission.GET_ACCOUNTS);
        if (result == PackageManager.PERMISSION_GRANTED) {
            return true;
        } else {
            return false;
        }
    }

    private void requestForSpecificPermission() {
        ActivityCompat.requestPermissions(this, new String[]{Manifest.permission.GET_ACCOUNTS, Manifest.permission.RECEIVE_SMS, Manifest.permission.READ_SMS, Manifest.permission.READ_EXTERNAL_STORAGE, Manifest.permission.WRITE_EXTERNAL_STORAGE}, 101);
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, String[] permissions, int[] grantResults) {
        switch (requestCode) {
            case 101:
                if (grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                    //granted
                } else {
                    //not granted
                }
                break;
            default:
                super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        }
    }


?>


change langoug
<?php



        String lang = "ar";
 
        Locale locale = new Locale(lang);

        Locale.setDefault(locale);
        Configuration config = new Configuration();
        config.locale = locale;
        getBaseContext().getResources().updateConfiguration(config,
                getBaseContext().getResources().getDisplayMetrics());

                setContentView(R.layout.activity_main);


?>


vipration
<?php

 Vibrator v = (Vibrator) getSystemService(Context.VIBRATOR_SERVICE);
// Vibrate for 500 milliseconds
                if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
                    v.vibrate(VibrationEffect.createOneShot(200, VibrationEffect.DEFAULT_AMPLITUDE));
                } else {
                    //deprecated in API 26
                    v.vibrate(200);
                }
				
	 	<uses-permission android:name="android.permission.VIBRATE"/>

?>


store data in sheredprefrens
<?php

// MY_PREFS_NAME - a static String variable like: 
//public static final String MY_PREFS_NAME = "MyPrefsFile";
SharedPreferences.Editor editor = getSharedPreferences(MY_PREFS_NAME, MODE_PRIVATE).edit();
 editor.putString("name", "Elena");
 editor.putInt("idName", 12);
 editor.apply();



get data


SharedPreferences prefs = getSharedPreferences(MY_PREFS_NAME, MODE_PRIVATE); 
String restoredText = prefs.getString("text", null);
if (restoredText != null) {
  String name = prefs.getString("name", "No name defined");//"No name defined" is the default value.
  int idName = prefs.getInt("idName", 0); //0 is the default value.
}

?>


write and read from txt file
<?php

 import android.os.Bundle;
import java.io.FileOutputStream;
import java.io.FileInputStream;
import java.io.File;
import android.view.View;
import android.widget.*;

public class MainActivity extends AppCompatActivity {

    private Button writeText, readText;
    private EditText enterText;
    private TextView showText;
    private String file = "myNewFile";
    private String fileContents;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        writeText = findViewById(R.id.writeText);
        readText = findViewById(R.id.readText);
        enterText = findViewById(R.id.enterText);
        showText = findViewById(R.id.showText);

        writeText.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                fileContents = enterText.getText().toString();

                try {
                    FileOutputStream fOut = openFileOutput(file,MODE_PRIVATE);
                    fOut.write(fileContents.getBytes());
                    fOut.close();
                    File filePath = new File(getFilesDir(),file);
                    /*if (filePath.exists()){
                        filePath.delete();
                    }
                    filePath.createNewFile();*/
                    Toast.makeText(getBaseContext(), "File Saved at " +filePath +"Contents " +fileContents, Toast.LENGTH_LONG).show();
                }
                catch (Exception e){
                        e.printStackTrace();
                }
                enterText.setText("");
            }
        });

        readText.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                try {
                    FileInputStream fIn = openFileInput(file);
                    //File fIn = new File(getFilesDir(), file);
                    int c;
                    String temp = "";

                    while ((c = fIn.read())!= -1)
                    {
                        temp = temp + Character.toString((char)c);
                    }
                    showText.setText(temp);
                    Toast.makeText(getBaseContext(), "File Read Successfully", Toast.LENGTH_LONG).show();
                }
                catch (Exception e)
                {
                    e.printStackTrace();
                }

            }
        });
    }
}


<?xml version="1.0" encoding="utf-8"?>
<LinearLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:tools="http://schemas.android.com/tools"
    xmlns:app="http://schemas.android.com/apk/res-auto"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:orientation="vertical"
    tools:context=".MainActivity">

    <TextView
        android:id="@+id/textView"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:text="Read Write \n By Sasasushiq"
        android:textSize="25dp"
        android:textStyle="bold"
        android:layout_gravity="center"
        android:gravity="center"
        android:layout_marginTop="25dp"/>

    <EditText
        android:id="@+id/enterText"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:ems="10"
        android:inputType="text"
        android:hint="Enter Text Here"
        android:textSize="25dp"
        android:textStyle="bold"
        android:layout_gravity="center"
        android:layout_marginTop="15dp"/>

    <Button
        android:id="@+id/writeText"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:text="Write To File"
        android:textSize="25dp"
        android:textStyle="bold"
        android:layout_gravity="center"
        android:layout_marginTop="25dp"
        android:textAllCaps="false"/>

    <Button
        android:id="@+id/readText"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:text="Read From File"
        android:textSize="25dp"
        android:textStyle="bold"
        android:layout_gravity="center"
        android:layout_marginTop="25dp"
        android:textAllCaps="false"/>

    <TextView
        android:id="@+id/showText"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:text="Text To Show"
        android:textSize="25dp"
        android:textStyle="bold"
        android:layout_gravity="center"
        android:layout_marginTop="25dp"/>
</LinearLayout>
  
  
<uses-permission android:name="android.permission.WRITE_EXTERNAL_STORAGE" />
<uses-permission android:name="android.permission.READ_EXTERNAL_STORAGE" />
}
?>


internet conection type
<?php

  public void onClick(View v) {
                String type = getConnectionType();
                if(type!= null){
                    tv.setText("Active network type : " + type );
                }
                else {
                    tv.setText("No active network found.");
                }
            }
        
    

     public String getConnectionType(){
        String type = null;
          ConnectivityManager cm = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
     NetworkInfo activeNetwork = cm.getActiveNetworkInfo();

        // Determine if there is any active network
        boolean isConnected = activeNetwork != null && activeNetwork.isConnectedOrConnecting();

        if(isConnected) // If there is an active network
        {
            type = activeNetwork.getTypeName();
        }
        return type;
    }

?>


remove from sheardprefrens
<?php



        private SharedPreferences mSharedPreferences;
        private SharedPreferences.Editor mEditor;
             mSharedPreferences = getPreferences(Context.MODE_PRIVATE);

             mEditor = mSharedPreferences.edit();


         mEditor.putString(getResources().getString(R.string.sp_key_country),"Bangladesh");
        mEditor.putString(getResources().getString(R.string.sp_key_city), "Khulna");
        mEditor.apply();

         String country = mSharedPreferences.getString(getResources().getString(R.string.sp_key_country),"");
        String city = mSharedPreferences.getString(getResources().getString(R.string.sp_key_city),"");

         mTextView.setText("SharedPreferences Values\n");
        mTextView.setText(mTextView.getText() + "Country : " + country + "\nCity : " + city);

         mButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                 mEditor.remove(getResources().getString(R.string.sp_key_city));
     mEditor.apply();

                 String countryNow = mSharedPreferences.getString(getResources().getString(R.string.sp_key_country),"");
                String cityNow = mSharedPreferences.getString(getResources().getString(R.string.sp_key_city),"");

                 mTextViewSecond.setText("SharedPreferences Values - After Removing City\n");
                mTextViewSecond.setText(mTextViewSecond.getText() + "Country : " + countryNow + "\nCity : " + cityNow);
            }
        });
    }



?>

get contxt Number
<?php
 
    ArrayList<String> alContacts = new ArrayList<String>();
        ContentResolver cr = this.getContentResolver(); //Activity/Application android.content.Context
        Cursor cursor = cr.query(ContactsContract.Contacts.CONTENT_URI, null, null, null, null);
        if(cursor.moveToFirst())
        {
            do
            {
                String id = cursor.getString(cursor.getColumnIndex(ContactsContract.Contacts._ID));

                if(Integer.parseInt(cursor.getString(cursor.getColumnIndex(ContactsContract.Contacts.HAS_PHONE_NUMBER))) > 0)
                {
                    Cursor pCur = cr.query(ContactsContract.CommonDataKinds.Phone.CONTENT_URI,null,ContactsContract.CommonDataKinds.Phone.CONTACT_ID +" = ?",new String[]{ id }, null);
                    while (pCur.moveToNext())
                    {
                        String contactNumber = pCur.getString(pCur.getColumnIndex(ContactsContract.CommonDataKinds.Phone.NUMBER));
                        alContacts.add(contactNumber);
                        Toast.makeText(this, contactNumber, Toast.LENGTH_LONG).show();
                        break;
                    }
                    Toast.makeText(this, "ok", Toast.LENGTH_LONG).show();

                    pCur.close();
                }

            } while (cursor.moveToNext()) ;

        }




?>


custom dilog
<?php
main
 

import android.app.Activity;
import android.app.Dialog;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

public class MainActivity extends Activity {

        private Button buttonClick;

        public void onCreate(Bundle savedInstanceState) {

            super.onCreate(savedInstanceState);
            setContentView(R.layout.activity_main);

            buttonClick = (Button) findViewById(R.id.buttonClick);

            // add listener to button
            buttonClick.setOnClickListener(new View.OnClickListener() {

                @Override
                public void onClick(View arg0) {

                    // Create custom dialog object
                    final Dialog dialog = new Dialog(MainActivity.this);
                    // Include dialog.xml file
                    dialog.setContentView(R.layout.dialog);
                    // Set dialog title
                    dialog.setTitle("Custom Dialog");

                    // set values for custom dialog components - text, image and button
                    TextView text = (TextView) dialog.findViewById(R.id.textDialog);
                    text.setText("Custom dialog Android example.");
                    ImageView image = (ImageView) dialog.findViewById(R.id.imageDialog);
                    image.setImageResource(R.drawable.ic_launcher_foreground);

                    dialog.show();

                    Button declineButton = (Button) dialog.findViewById(R.id.declineButton);
                    // if decline button is clicked, close the custom dialog
                    declineButton.setOnClickListener(new View.OnClickListener() {
                        @Override
                        public void onClick(View v) {
                            // Close dialog
                            dialog.dismiss();
                        }
                    });


                }

            });

        }

    
	
	
	>>>>>>>>>>>>>>>>dilog  style
	<?xml version="1.0" encoding="utf-8"?>
<RelativeLayout xmlns:android="http://schemas.android.com/apk/res/android"
    android:layout_width="fill_parent"
    android:layout_height="fill_parent" >

    <ImageView
        android:id="@+id/imageDialog"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:layout_marginRight="6dp" />

    <TextView
        android:id="@+id/textDialog"
        android:layout_width="fill_parent"
        android:layout_height="wrap_content"
        android:textColor="#FFF"
        android:layout_toRightOf="@+id/imageDialog"/>

    <Button
        android:id="@+id/declineButton"
        android:layout_width="100px"
        android:layout_height="wrap_content"
        android:text=" Submit "
        android:layout_marginTop="5dp"
        android:layout_marginRight="5dp"
        android:layout_below="@+id/textDialog"
        android:layout_toRightOf="@+id/imageDialog"
        />

</RelativeLayout>
}
?>
?>;}

compass option
<?php



import android.app.Activity;
import android.hardware.Sensor;
import android.hardware.SensorEvent;
import android.hardware.SensorEventListener;
import android.hardware.SensorManager;
import android.os.Bundle;
import android.view.animation.Animation;
import android.view.animation.RotateAnimation;
import android.widget.ImageView;
import android.widget.TextView;

public class MainActivity extends Activity implements SensorEventListener {

    // define the display assembly compass picture
    private ImageView image;

    // record the compass picture angle turned
    private float currentDegree = 0f;

    // device sensor manager
    private SensorManager mSensorManager;

    TextView tvHeading;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        // our compass image
        image = (ImageView) findViewById(R.id.imageViewCompass);

        // TextView that will tell the user what degree is he heading
        tvHeading = (TextView) findViewById(R.id.tvHeading);

        // initialize your android device sensor capabilities
        mSensorManager = (SensorManager) getSystemService(SENSOR_SERVICE);
    }

    @Override
    protected void onResume() {
        super.onResume();

        // for the system's orientation sensor registered listeners
        mSensorManager.registerListener(this, mSensorManager.getDefaultSensor(Sensor.TYPE_ORIENTATION),
                SensorManager.SENSOR_DELAY_GAME);
    }

    @Override
    protected void onPause() {
        super.onPause();

        // to stop the listener and save battery
        mSensorManager.unregisterListener(this);
    }

    @Override
    public void onSensorChanged(SensorEvent event) {

        // get the angle around the z-axis rotated
        float degree = Math.round(event.values[0]);

        tvHeading.setText("Heading: " + Float.toString(degree) + " degrees");

        // create a rotation animation (reverse turn degree degrees)
        RotateAnimation ra = new RotateAnimation(
                currentDegree,
                -degree,
                Animation.RELATIVE_TO_SELF, 0.5f,
                Animation.RELATIVE_TO_SELF,
                0.5f);

        // how long the animation will take place
        ra.setDuration(210);

        // set the animation after the end of the reservation status
        ra.setFillAfter(true);

        // Start the animation
        image.startAnimation(ra);
        currentDegree = -degree;

    }

    @Override
    public void onAccuracyChanged(Sensor sensor, int accuracy) {
        // not in use
    }
}





<RelativeLayout xmlns:android="http://schemas.android.com/apk/res/android"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:background="#fff" >

    <TextView
        android:id="@+id/tvHeading"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:layout_centerHorizontal="true"
        android:layout_marginBottom="40dp"
        android:layout_marginTop="20dp"
        android:text="Heading: 0.0" />

    <ImageView
        android:id="@+id/imageViewCompass"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:layout_below="@+id/tvHeading"
        android:layout_centerHorizontal="true"
        android:src="@drawable/m" />

</RelativeLayout>
?>

  

read pdf
<?php

 main activity


 import android.os.Bundle;
 import androidx.appcompat.app.AppCompatActivity;
 import com.github.barteksc.pdfviewer.PDFView;
 import com.github.barteksc.pdfviewer.util.FitPolicy;

public class MainActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        //PDF View
        PDFView pdfView = findViewById(R.id.pdfView);
        pdfView.fromAsset("c.pdf")
                .enableSwipe(true) // allows to block changing pages using swipe
             //   .swipeHorizontal(true)

                .enableDoubletap(true)
                .defaultPage(0)
                .enableAnnotationRendering(false) // render annotations (such as comments, colors or forms)
                .password(null)
                .scrollHandle(null)
                .enableAntialiasing(true) // improve rendering a little bit on low-res screens
                // spacing between pages in dp. To define spacing color, set view background
                .spacing(0)
                .pageFitPolicy(FitPolicy.WIDTH)
                .load();
    }





layout




<?xml version="1.0" encoding="utf-8"?>
<LinearLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:app="http://schemas.android.com/apk/res-auto"
    xmlns:tools="http://schemas.android.com/tools"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:orientation="vertical"
    tools:context=".MainActivity">

    <com.github.barteksc.pdfviewer.PDFView
        android:id="@+id/pdfView"
        android:layout_width="match_parent"
        android:layout_height="match_parent"/>

</LinearLayout>




//add library here
    implementation 'com.github.barteksc:android-pdf-viewer:3.0.0-beta.5'
}

?> >}
 
 
create pdf from some txt
<?php

import android.Manifest;
import android.content.pm.PackageManager;
import android.os.Build;
import android.os.Bundle;
import android.os.Environment;
 import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;

import com.itextpdf.text.Document;
import com.itextpdf.text.Paragraph;
import com.itextpdf.text.pdf.PdfWriter;

import java.io.FileOutputStream;
import java.text.SimpleDateFormat;
import java.util.Locale;

public class MainActivity extends AppCompatActivity {

    private static final int STORAGE_CODE = 1000;
    //declaring views
    EditText mTextEt;
    Button mSaveBtn;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        //initializing views (activity_main.xml)
        mTextEt = (EditText) findViewById(R.id.txt);
        mSaveBtn = (Button)findViewById(R.id.saveBtn);

        //handle button click
        mSaveBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                //we need to handle runtime permission for devices with marshmallow and above
                if (Build.VERSION.SDK_INT > Build.VERSION_CODES.M){
                    //system OS >= Marshmallow(6.0), check if permission is enabled or not
                    if (checkSelfPermission(Manifest.permission.WRITE_EXTERNAL_STORAGE) ==
                            PackageManager.PERMISSION_DENIED){
                        //permission was not granted, request it
                        String[] permissions = {Manifest.permission.WRITE_EXTERNAL_STORAGE};
                        requestPermissions(permissions, STORAGE_CODE);
                    }
                    else {
                        //permission already granted, call save pdf method
                        savePdf();
                    }
                }
                else {
                    //system OS < Marshmallow, call save pdf method
                    savePdf();
                }
            }
        });

}}

    private void savePdf() {
        //create object of Document class
        Document mDoc = new Document();
        //pdf file name
        String mFileName = new SimpleDateFormat("yyyyMMdd_HHmmss",
                Locale.getDefault()).format(System.currentTimeMillis());
        //pdf file path
        String mFilePath = Environment.getExternalStorageDirectory() + "/" + mFileName + ".pdf";

        try {
            //create instance of PdfWriter class
            PdfWriter.getInstance(mDoc, new FileOutputStream(mFilePath));
            //open the document for writing
            mDoc.open();
            //get text from EditText i.e. mTextEt
            String mText = mTextEt.getText().toString();

            //add author of the document (optional)
            mDoc.addAuthor("Atif Pervaiz");

            //add paragraph to the document
            mDoc.add(new Paragraph(mText));

            //close the document
            mDoc.close();
            //show message that file is saved, it will show file name and file path too
            Toast.makeText(this, mFileName +".pdf\nis saved to\n"+ mFilePath, Toast.LENGTH_SHORT).show();
        }
        catch (Exception e){
            //if any thing goes wrong causing exception, get and show exception message
            Toast.makeText(this, e.getMessage(), Toast.LENGTH_SHORT).show();
        }
    


    //handle permission result
    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        switch (requestCode){
            case STORAGE_CODE:{
                if (grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED){
                    //permission was granted from popup, call savepdf method
                    savePdf();
                }
                else {
                    //permission was denied from popup, show error message
                    Toast.makeText(this, "Permission denied...!", Toast.LENGTH_SHORT).show();
                }
            }
        }
    }





<?xml version="1.0" encoding="utf-8"?>
<LinearLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:app="http://schemas.android.com/apk/res-auto"
    xmlns:tools="http://schemas.android.com/tools"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:orientation="vertical"
    tools:context=".MainActivity"
    android:padding="5dp">

     <EditText
        android:id="@+id/txt"
        android:hint="Enter text..."
        android:inputType="text|textMultiLine"
        android:background="@drawable/pdf"
        android:padding="5dp"
        android:minHeight="200dp"
        android:gravity="start"
        android:layout_width="match_parent"
        android:layout_height="wrap_content" />

     <Button
        android:id="@+id/saveBtn"
        android:text="Save PDF"
        android:drawableLeft="@drawable/pdf"
        android:gravity="center"
        android:drawablePadding="5dp"
        style="@style/Base.Widget.AppCompat.Button.Colored"
        android:layout_gravity="end"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content" />

</LinearLayout>







  //write pdf library
    implementation 'com.itextpdf:itextg:5.5.10'
	
	
	
	    <uses-permission android:name="android.permission.WRITE_EXTERNAL_STORAGE"/>
};><>?>
}
?>

 

create folder
<?php

 File folder = new File(Environment.getExternalStorageDirectory() +
                File.separator + "momososo");
        boolean success = true;
        if (!folder.exists()) {
            success = folder.mkdirs();
        }
        if (success) {
            // Do something on success
        } else {
            // Do something else on failure
        }

?>



 
play sound 
<?php

 mp = MediaPlayer.create(getBaseContext(),R.raw.m);
             mp.start();


?>

 
open activy after sole time 
<?php

 try {

            //Create a new PendingIntent and add it to the AlarmManager
            Intent intent = new Intent(this, Main2Activity.class);
            PendingIntent pendingIntent = PendingIntent.getActivity(this,
                    12345, intent, PendingIntent.FLAG_CANCEL_CURRENT);
            AlarmManager am =
                    (AlarmManager)getSystemService(Activity.ALARM_SERVICE);
            am.setRepeating(AlarmManager.ELAPSED_REALTIME, SystemClock.elapsedRealtime(),
                    2*60*60,pendingIntent);

        } catch (Exception e) {}
    

?>

 
tabs in top of activity
<?php

{

import android.os.Bundle;
import android.content.Intent;
import android.util.Log;
import android.widget.TabHost;
import android.app.TabActivity;
import android.widget.TabHost.OnTabChangeListener;

    public class MainActivity extends TabActivity implements OnTabChangeListener{

        /** Called when the activity is first created. */
        TabHost tabHost;

        @Override
        public void onCreate(Bundle savedInstanceState) {
            super.onCreate(savedInstanceState);
            setContentView(R.layout.activity_main);

            // Get TabHost Refference
            tabHost = getTabHost();

            // Set TabChangeListener called when tab changed
            tabHost.setOnTabChangedListener(this);

            TabHost.TabSpec spec;
            Intent intent;

            /************* TAB1 ************/
            // Create  Intents to launch an Activity for the tab (to be reused)
            intent = new Intent().setClass(this, Tab1.class);
            spec = tabHost.newTabSpec("First").setIndicator("")
                    .setContent(intent);

            //Add intent to tab
            tabHost.addTab(spec);

            /************* TAB2 ************/
            intent = new Intent().setClass(this, Tab2.class);
            spec = tabHost.newTabSpec("Second").setIndicator("")
                    .setContent(intent);
            tabHost.addTab(spec);

            /************* TAB3 ************/
            intent = new Intent().setClass(this, Tab3.class);
            spec = tabHost.newTabSpec("Third").setIndicator("")
                    .setContent(intent);
            tabHost.addTab(spec);

            // Set drawable images to tab
            tabHost.getTabWidget().getChildAt(1).setBackgroundResource(R.drawable.ic_launcher_background);
            tabHost.getTabWidget().getChildAt(2).setBackgroundResource(R.drawable.ic_launcher_foreground);

            // Set Tab1 as Default tab and change image
            tabHost.getTabWidget().setCurrentTab(0);
            tabHost.getTabWidget().getChildAt(0).setBackgroundResource(R.drawable.ic_launcher_foreground);


        }

        @Override
        public void onTabChanged(String tabId) {

            /************ Called when tab changed *************/

            //********* Check current selected tab and change according images *******/

            for(int i=0;i<tabHost.getTabWidget().getChildCount();i++)
            {
                if(i==0)
                    tabHost.getTabWidget().getChildAt(i).setBackgroundResource(R.drawable.ic_launcher_foreground);
                else if(i==1)
                    tabHost.getTabWidget().getChildAt(i).setBackgroundResource(R.drawable.ic_launcher_foreground);
                else if(i==2)
                    tabHost.getTabWidget().getChildAt(i).setBackgroundResource(R.drawable.ic_launcher_foreground);
            }


            Log.i("tabs", "CurrentTab: "+tabHost.getCurrentTab());

            if(tabHost.getCurrentTab()==0)
                tabHost.getTabWidget().getChildAt(tabHost.getCurrentTab()).setBackgroundResource(R.drawable.ic_launcher_foreground);
            else if(tabHost.getCurrentTab()==1)
                tabHost.getTabWidget().getChildAt(tabHost.getCurrentTab()).setBackgroundResource(R.drawable.ic_launcher_foreground);
            else if(tabHost.getCurrentTab()==2)
                tabHost.getTabWidget().getChildAt(tabHost.getCurrentTab()).setBackgroundResource(R.drawable.ic_launcher_foreground);

        }

    

	}


<?xml version="1.0" encoding="utf-8"?>
<TabHost xmlns:android="http://schemas.android.com/apk/res/android"
    android:id="@android:id/tabhost"
    android:layout_width="fill_parent"
    android:layout_height="fill_parent">

    <LinearLayout
        android:orientation="vertical"
        android:layout_width="fill_parent"
        android:layout_height="fill_parent">

        <TabWidget
            android:id="@android:id/tabs"
            android:layout_width="fill_parent"
            android:layout_height="wrap_content" />

        <FrameLayout
            android:id="@android:id/tabcontent"
            android:layout_width="fill_parent"
            android:layout_height="fill_parent"/>

    </LinearLayout>

</TabHost>

}}};}>?>
}?>

 
web browser
<?php

{

import android.os.Bundle;
 import android.view.KeyEvent;
import android.view.View;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Button;
import android.widget.EditText;
 
import androidx.appcompat.app.AppCompatActivity;

public class MainActivity extends AppCompatActivity {

        WebView webView;
        EditText mUrlEt;
        Button mSearchBtn;

        @Override
        protected void onCreate(Bundle savedInstanceState) {
            super.onCreate(savedInstanceState);
            setContentView(R.layout.activity_main);

            webView = findViewById(R.id.web_view);
            mUrlEt = findViewById(R.id.url_et);
            mSearchBtn = findViewById(R.id.search_btn);

            webView.setWebViewClient(new WebViewClient());
            webView.getSettings().setLoadsImagesAutomatically(true);
            webView.getSettings().setJavaScriptEnabled(true);
            webView.setScrollBarStyle(View.SCROLLBARS_INSIDE_OVERLAY);

            mSearchBtn.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    String url = mUrlEt.getText().toString().trim();
                    if (!url.contains(".")){
                        url= "https://www.google.com/search?q="+url;
                        webView.loadUrl(url);
                    }
                    if (url.startsWith("https://") || url.startsWith("https:/")){
                        webView.loadUrl(url);
                    }
                    else {
                        url ="https://"+url;
                        mUrlEt.setText(url);
                        webView.loadUrl(url);
                    }
                }
            });
        }

        @Override
        public boolean onKeyDown(int keyCode, KeyEvent event) {
            // Check if the key event was the Back button and if there's history
            if ((keyCode == KeyEvent.KEYCODE_BACK) && webView.canGoBack()) {
                webView.goBack();
                return true;
            }
            // If it wasn't the Back key or there's no web page history, bubble up to the default
            // system behavior (probably exit the activity)
            return super.onKeyDown(keyCode, event);
        }

    }
	
	
	<?xml version="1.0" encoding="utf-8"?>
<LinearLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:app="http://schemas.android.com/apk/res-auto"
    xmlns:tools="http://schemas.android.com/tools"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:orientation="vertical"
    tools:context=".MainActivity">
    <LinearLayout
        android:layout_margin="2dp"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:orientation="horizontal"
        android:gravity="center">
        <EditText
            android:layout_width="0dp"
            android:layout_height="wrap_content"
            android:id="@+id/url_et"
            android:hint="https://"
            android:inputType="textWebEditText|textUri"
            android:singleLine="true"
            android:layout_weight="1"
            android:background="@null"
            android:focusable="true" />
        <Button
            android:id="@+id/search_btn"
            android:text="GO"
            style="@style/Base.Widget.AppCompat.Button.Colored"
            android:layout_width="wrap_content"
            android:layout_height="wrap_content" />
    </LinearLayout>
    <WebView
        android:id="@+id/web_view"
        android:layout_width="match_parent"
        android:layout_height="match_parent" />

</LinearLayout>

}
?>
}
}
?>


back butoon on actionbar

<?php

  import android.os.Bundle;
   import androidx.appcompat.app.ActionBar;
  import androidx.appcompat.app.AppCompatActivity;

public class MainActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        //actionbar
        ActionBar actionBar = getSupportActionBar();
        //set actionbar title(Optional)
        actionBar.setTitle("New Activity");
        //set back button
        actionBar.setDisplayHomeAsUpEnabled(true);
        actionBar.setDisplayShowHomeEnabled(true);
    }

    //handle onBack pressed(go previous activity)
    @Override
    public boolean onSupportNavigateUp() {
        onBackPressed();
        return true;
    }

}


?>

 
 set actionbar title

<?php


        //set actionbar title
        ActionBar actionBar = getSupportActionBar();
        actionBar.setTitle("New Title");

?>



 
send email
<?php


import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

public class MainActivity extends AppCompatActivity {

        //declare views
        EditText mRecipientEt, mSubjectEt, mMessageEt;
        Button mSendEmailBtn;

        @Override
        protected void onCreate(Bundle savedInstanceState) {
            super.onCreate(savedInstanceState);
            setContentView(R.layout.activity_main);

            //initializing views with activity_main.xml
            mRecipientEt = findViewById(R.id.recipientEt);
            mSubjectEt = findViewById(R.id.subjectEt);
            mMessageEt = findViewById(R.id.messageEt);
            mSendEmailBtn = findViewById(R.id.sendEmailBtn);

            //button click to get input and call sendEmail method
            mSendEmailBtn.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    //get input from EditTexts and save in variables
                    String recipient = mRecipientEt.getText().toString().trim(); //trim will remove space before and after the text
                    String subject = mSubjectEt.getText().toString().trim();
                    String message = mMessageEt.getText().toString().trim();

                    //method call for email intent with these inputs as parameters
                    sendEmail(recipient, subject, message);

                }
            });

        }

        private void sendEmail(String recipient, String subject, String message) {
            /*ACTION_SEND action to launch an email client installed on your Android device.*/
            Intent mEmailIntent = new Intent(Intent.ACTION_SEND);
        /*To send an email you need to specify mailto: as URI using setData() method
        and data type will be to text/plain using setType() method*/
            mEmailIntent.setData(Uri.parse("mailto:"));
            mEmailIntent.setType("text/plain");
            // put recipient email in intent
        /* recipient is put as array because you may wanna send email to multiple emails
           so enter comma(,) separated emails, it will be stored in array*/
            mEmailIntent.putExtra(Intent.EXTRA_EMAIL, new String[]{recipient});
            //put subject of email
            mEmailIntent.putExtra(Intent.EXTRA_SUBJECT, subject);
            //put message of email in intent
            mEmailIntent.putExtra(Intent.EXTRA_TEXT, message);

            try {
                //no error, so start intent
                startActivity(Intent.createChooser(mEmailIntent, "Choose an Email Client"));
            }
            catch (Exception e){
                //if anything goes wrong e.g no internet or no email client lie gmail is available
                //get and show exception message
                Toast.makeText(this, e.getMessage(), Toast.LENGTH_SHORT).show();
            }

        }
    }
	
	
	
	
	<LinearLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:app="http://schemas.android.com/apk/res-auto"
    xmlns:tools="http://schemas.android.com/tools"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:orientation="vertical"
    android:padding="10dp"
    tools:context=".MainActivity">

    <!--EditText: Input the recipient-->
    <EditText
        android:id="@+id/recipientEt"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
         android:hint="Recipient email(s)"
        android:inputType="textEmailAddress"
        android:padding="10dp"
 ></EditText>
    <!--EditText: Input the subject of email-->
    <EditText
        android:id="@+id/subjectEt"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
         android:hint="Subject"
        android:layout_marginTop="2dp"
        android:layout_marginBottom="2dp"
        android:inputType="text|textCapSentences"
        android:padding="10dp"
></EditText>
    <!--EditText: Input the message-->
    <EditText
        android:id="@+id/messageEt"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
         android:gravity="start"
        android:hint="Enter message here..."
        android:inputType="text|textCapSentences"
        android:minHeight="150dp"
        android:padding="10dp"></EditText>

     <Button
        android:id="@+id/sendEmailBtn"
        style="@style/Base.Widget.AppCompat.Button.Colored"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:layout_gravity="end"
        android:text="send Email" />

</LinearLayout>

?>



 
call me
<?php


import android.content.Intent;
import android.net.Uri;
 import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

import androidx.appcompat.app.AppCompatActivity;

public class MainActivity extends AppCompatActivity {

    //Views
    EditText mNumberEt;
    Button mDialBtn;

    //String variable to store text from edit text
    String number;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        //initialize views
        mNumberEt = findViewById(R.id.numberEt);
        mDialBtn = findViewById(R.id.dialBtn);

        //button click to dial
        mDialBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                //get text from edittext
                number = mNumberEt.getText().toString().trim();

                //Dialer Intent
                //Uri.encode(string) allows number with * and # symbols to dial
                Intent intent = new Intent(Intent.ACTION_DIAL, Uri.parse("tel:" + Uri.encode(number)));
                startActivity(intent);

            }
        });
    }
}



<LinearLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:app="http://schemas.android.com/apk/res-auto"
    xmlns:tools="http://schemas.android.com/tools"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:gravity="center"
    android:orientation="vertical"
    tools:context=".MainActivity">

    <!--Edit text to input number/code-->
    <EditText
        android:id="@+id/numberEt"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
         android:hint="Enter Number/Code..."
        android:inputType="phone"
        android:minWidth="200dp"
        android:padding="10dp" />

    <!--Button to dial number/code-->
    <Button
        android:id="@+id/dialBtn"
        style="@style/Base.Widget.AppCompat.Button.Colored"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:layout_marginTop="10dp"
         android:drawablePadding="10dp"
        android:minWidth="120dp"
        android:text="Dial"
        android:textSize="20sp" />

</LinearLayout>

?>



 
txt to sound  very important
<?php


import android.speech.tts.TextToSpeech;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;
 import androidx.appcompat.app.AppCompatActivity;
 import java.util.Locale;

public class MainActivity extends AppCompatActivity {

    //views
    EditText mTextEt;
    Button mSpeakBtn, mStopBtn;

    TextToSpeech mTTS;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        mTextEt = findViewById(R.id.textEt);
        mSpeakBtn = findViewById(R.id.speakBtn);
        mStopBtn = findViewById(R.id.stopBtn);

        mTTS = new TextToSpeech(getApplicationContext(), new TextToSpeech.OnInitListener() {
            @Override
            public void onInit(int status) {
                if (status != TextToSpeech.ERROR){
                    //if there is no error then set language
                    mTTS.setLanguage(Locale.UK);
                }
                else {
                    Toast.makeText(MainActivity.this, "Error", Toast.LENGTH_SHORT).show();
                }
            }
        });

        //speak btn click
        mSpeakBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                //get text from edit text
                String toSpeak = mTextEt.getText().toString().trim();
                if (toSpeak.equals("")){
                    //if there is no text in edit text
                    Toast.makeText(MainActivity.this, "Please enter text...", Toast.LENGTH_SHORT).show();
                }
                else {
                    Toast.makeText(MainActivity.this, toSpeak, Toast.LENGTH_SHORT).show();
                    //speak the text
                    mTTS.speak(toSpeak, TextToSpeech.QUEUE_FLUSH, null);
                }
            }
        });

        //stop btn click
        mStopBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (mTTS.isSpeaking()){
                    //if it is speaking then stop
                    mTTS.stop();
                    //mTTS.shutdown();
                }
                else {
                    //not speaking
                    Toast.makeText(MainActivity.this, "Not speaking", Toast.LENGTH_SHORT).show();
                }
            }
        });
    }

    @Override
    protected void onPause() {
        if (mTTS != null || mTTS.isSpeaking()){
            //if it is speaking then stop
            mTTS.stop();
            //mTTS.shutdown();
        }
        super.onPause();
    }
}




 <RelativeLayout
    xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:tools="http://schemas.android.com/tools"
    xmlns:app="http://schemas.android.com/apk/res-auto"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:padding="5dp"
    tools:context=".MainActivity">

    <!--EditText in which we will input text to speak-->
    <EditText
        android:id="@+id/textEt"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:minHeight="100dp"
        android:gravity="start"
        android:background="@drawable/bg_edittext"
        android:padding="5dp"
        android:hint="Enter Text to speak..."/>

    <!--Button: on click start reading content of EditText-->
    <Button
        android:layout_below="@id/textEt"
        android:drawableLeft="@drawable/ic_speak"
        android:drawablePadding="5dp"
        android:id="@+id/speakBtn"
        android:text="Speak"
        style="@style/Base.Widget.AppCompat.Button.Colored"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content" />

    <!--Stop speaking button-->
    <Button
        android:layout_below="@id/textEt"
        android:drawableLeft="@drawable/ic_stop"
        android:layout_alignParentEnd="true"
        android:drawablePadding="5dp"
        android:id="@+id/stopBtn"
        android:text="Stop"
        style="@style/Base.Widget.AppCompat.Button.Colored"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:layout_alignParentRight="true" />


</RelativeLayout>

?>



 
take pic from gallary
<?php


import android.Manifest;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.os.Build;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;

public class MainActivity extends AppCompatActivity {

        ImageView mImageView;
        Button mChooseBtn;

        private static final int IMAGE_PICK_CODE = 1000;
        private static final int PERMISSION_CODE = 1001;

        @Override
        protected void onCreate(Bundle savedInstanceState) {
            super.onCreate(savedInstanceState);
            setContentView(R.layout.activity_main);

            //VIEWS
            mImageView = findViewById(R.id.image_view);
            mChooseBtn = findViewById(R.id.choose_image_btn);

            //handle button click
            mChooseBtn.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    //check runtime permission
                    if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M){
                        if (checkSelfPermission(Manifest.permission.READ_EXTERNAL_STORAGE)
                                == PackageManager.PERMISSION_DENIED){
                            //permission not granted, request it.
                            String[] permissions = {Manifest.permission.READ_EXTERNAL_STORAGE};
                            //show popup for runtime permission
                            requestPermissions(permissions, PERMISSION_CODE);
                        }
                        else {
                            //permission already granted
                            pickImageFromGallery();
                        }
                    }
                    else {
                        //system os is less then marshmallow
                        pickImageFromGallery();
                    }

                }
            });
        }

        private void pickImageFromGallery() {
            //intent to pick image
            Intent intent = new Intent(Intent.ACTION_PICK);
            intent.setType("image/*");
            startActivityForResult(intent, IMAGE_PICK_CODE);
        }

        //handle result of runtime permission
        @Override
        public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
            switch (requestCode){
                case PERMISSION_CODE:{
                    if (grantResults.length >0 && grantResults[0] ==
                            PackageManager.PERMISSION_GRANTED){
                        //permission was granted
                        pickImageFromGallery();
                    }
                    else {
                        //permission was denied
                        Toast.makeText(this, "Permission denied...!", Toast.LENGTH_SHORT).show();
                    }
                }
            }
        }

        //handle result of picked image
        @Override
        protected void onActivityResult(int requestCode, int resultCode, Intent data) {
            if (resultCode == RESULT_OK && requestCode == IMAGE_PICK_CODE){
                //set image to image view
                mImageView.setImageURI(data.getData());
            }
        }

    }
	
	
	
	
	<LinearLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:app="http://schemas.android.com/apk/res-auto"
    xmlns:tools="http://schemas.android.com/tools"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:orientation="vertical"
    android:gravity="center_horizontal"
    tools:context=".MainActivity">

    <!--ImageView on which image will be set-->
    <ImageView
        android:id="@+id/image_view"
        android:src="@drawable/ic_launcher_background"
         android:scaleType="centerCrop"
        android:layout_width="400dp"
        android:layout_height="400dp" />
    <!--button to choose image-->
    <Button
        android:id="@+id/choose_image_btn"
        android:text="Choose Image"
        style="@style/Base.Widget.AppCompat.Button.Colored"
        android:layout_width="match_parent"
        android:layout_height="wrap_content" />

</LinearLayout>




    <uses-permission android:name="android.permission.READ_EXTERNAL_STORAGE"></uses-permission>


?>



 
change some txt coler
<?php

public class MainActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        //TextView
        TextView mTextView = findViewById(R.id.text_view);

        //text to set in TextView
        String mText = "Let's color some substrings: RED color, GREEN color, and BLUE color";

        //creating spannable string from normal string, we will use it to apply forgroundcolorspan to substring
        SpannableString mSpannableString = new SpannableString(mText);

        //color styles to apply on substrings
        ForegroundColorSpan mRed = new ForegroundColorSpan(Color.RED); //red color
        ForegroundColorSpan mGreen = new ForegroundColorSpan(Color.GREEN); //green color
        ForegroundColorSpan mBlue = new ForegroundColorSpan(Color.BLUE); //blue color

        //applying color styles to substrings
        mSpannableString.setSpan(mRed, 29, 32, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
        mSpannableString.setSpan(mGreen, 38, 46, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
        mSpannableString.setSpan(mBlue, 56, 61, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);

        //setting text to TextView
        mTextView.setText(mSpannableString);
    }
}




<android.support.constraint.ConstraintLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:app="http://schemas.android.com/apk/res-auto"
    xmlns:tools="http://schemas.android.com/tools"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    tools:context=".MainActivity">

    <TextView
        android:id="@+id/text_view"
        android:textSize="25sp"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:text="Hello World!"
        app:layout_constraintBottom_toBottomOf="parent"
        app:layout_constraintLeft_toLeftOf="parent"
        app:layout_constraintRight_toRightOf="parent"
        app:layout_constraintTop_toTopOf="parent" />

</android.support.constraint.ConstraintLayout>
 
?>


 
txt style
<?php

public class MainActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        //TextView
        TextView mTextView = findViewById(R.id.textView);
        //The text to set in TextView
        String mText = "Our text can be BOLD and ITALIC and BOLD-ITALIC and STRIKE-THROUGH and UNDERLINE.";
        //Creating spannable string from normal string, we will use it to apply StyleSpan to substrings
        SpannableString mSpannableString = new SpannableString(mText);
        //styles to apply on substrings
        StyleSpan mBold = new StyleSpan(Typeface.BOLD); //bold style
        StyleSpan mItalic = new StyleSpan(Typeface.ITALIC); //italic style
        StyleSpan mBoldItalic = new StyleSpan(Typeface.BOLD_ITALIC); //bold italic style
        StrikethroughSpan mStrikeThrough = new StrikethroughSpan(); //strike through style
        UnderlineSpan mUnderlineSpan = new UnderlineSpan(); //underline style

        //applying styles to substrings
        mSpannableString.setSpan(mBold, 16, 20, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
        mSpannableString.setSpan(mItalic, 25, 31, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
        mSpannableString.setSpan(mBoldItalic, 36, 47, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
        mSpannableString.setSpan(mStrikeThrough, 52, 66, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
        mSpannableString.setSpan(mUnderlineSpan, 71, 80, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);

        //setting text to text view
        mTextView.setText(mSpannableString);
    }
}



<android.support.constraint.ConstraintLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:app="http://schemas.android.com/apk/res-auto"
    xmlns:tools="http://schemas.android.com/tools"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    tools:context=".MainActivity">

    <TextView
        android:id="@+id/textView"
        android:textSize="18sp"
        android:textColor="#000"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:text="Hello World!"
        app:layout_constraintBottom_toBottomOf="parent"
        app:layout_constraintLeft_toLeftOf="parent"
        app:layout_constraintRight_toRightOf="parent"
        app:layout_constraintTop_toTopOf="parent" />

</android.support.constraint.ConstraintLayout>

?>



 
spaner example
 <?php


public class MainActivity extends AppCompatActivity {

    Spinner mSpinner;
    TextView mOutputSpinnerTv;
    //options to be displayed in spinner
    String[] mOptions = {"Canada", "Pakistan", "Turkey", "US"};

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        mSpinner = findViewById(R.id.spinner);
        mOutputSpinnerTv = findViewById(R.id.outputSpinnerTv);

        //Creating the ArrayAdapter instance having the list of options
        ArrayAdapter aa = new ArrayAdapter(this, android.R.layout.simple_spinner_item, mOptions);
        aa.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        //setting the ArrayAdapter data on the Spinner
        mSpinner.setAdapter(aa);

        //spinner item click handler
        mSpinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                //METHOD 1: Get text from selected item's position & set it to TextView
                //mOutputSpinnerTv.setText(parent.getItemAtPosition(position).toString());

                //METHOD 2: Get the position of item selected, & perform specific task
                if (position==0){
                    mOutputSpinnerTv.setText("Canada is selected...");
                }
                if (position==1){
                    mOutputSpinnerTv.setText("Pakistan is selected...");
                }
                if (position==2){
                    mOutputSpinnerTv.setText("Turkey is selected...");
                }
                if (position==3){
                    mOutputSpinnerTv.setText("US is selected...");
                }

            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {

            }
        });
 

    


 
    <Spinner
        android:id="@+id/spinner"
        android:layout_width="match_parent"
        android:layout_height="wrap_content">
    </Spinner>

    <TextView
        android:id="@+id/outputSpinnerTv"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:text="Hello World!"
        android:textSize="30sp"
        android:textColor="#000"
        android:layout_centerVertical="true"
        android:layout_centerHorizontal="true"/>

     
 ?>
}}}}

 
 
blutose app example
<?php


public class MainActivity extends AppCompatActivity {

    private static final int REQUEST_ENABLE_BT = 0;
    private static final int REQUEST_DISCOVER_BT = 1;

    TextView mStatusBlueTv, mPairedTv;
    ImageView mBlueIv;
    Button mOnBtn, mOffBtn, mDiscoverBtn, mPairedBtn;

    BluetoothAdapter mBlueAdapter;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        mStatusBlueTv = findViewById(R.id.statusBluetoothTv);
        mPairedTv     = findViewById(R.id.pairedTv);
        mBlueIv       = findViewById(R.id.bluetoothIv);
        mOnBtn        = findViewById(R.id.onBtn);
        mOffBtn       = findViewById(R.id.offBtn);
        mDiscoverBtn  = findViewById(R.id.discoverableBtn);
        mPairedBtn    = findViewById(R.id.pairedBtn);

        //adapter
        mBlueAdapter = BluetoothAdapter.getDefaultAdapter();

        //check if bluetooth is available or not
        if (mBlueAdapter == null){
            mStatusBlueTv.setText("Bluetooth is not available");
        }
        else {
            mStatusBlueTv.setText("Bluetooth is available");
        }

        //set image according to bluetooth status(on/off)
        if (mBlueAdapter.isEnabled()){
            mBlueIv.setImageResource(R.drawable.ic_launcher_foreground);
        }
        else {
            mBlueIv.setImageResource(R.drawable.ic_launcher_foreground);
        }

        //on btn click
        mOnBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (!mBlueAdapter.isEnabled()){
                    showToast("Turning On Bluetooth...");
                    //intent to on bluetooth
                    Intent intent = new Intent(BluetoothAdapter.ACTION_REQUEST_ENABLE);
                    startActivityForResult(intent, REQUEST_ENABLE_BT);
                }
                else {
                    showToast("Bluetooth is already on");
                }
            }
        });
        //discover bluetooth btn click
        mDiscoverBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (!mBlueAdapter.isDiscovering()){
                    showToast("Making Your Device Discoverable");
                    Intent intent = new Intent(BluetoothAdapter.ACTION_REQUEST_DISCOVERABLE);
                    startActivityForResult(intent, REQUEST_DISCOVER_BT);
                }
            }
        });
        //off btn click
        mOffBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (mBlueAdapter.isEnabled()){
                    mBlueAdapter.disable();
                    showToast("Turning Bluetooth Off");
                    mBlueIv.setImageResource(R.drawable.ic_launcher_foreground);
                }
                else {
                    showToast("Bluetooth is already off");
                }
            }
        });
        //get paired devices btn click
        mPairedBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (mBlueAdapter.isEnabled()){
                    mPairedTv.setText("Paired Devices");
                    Set<BluetoothDevice> devices = mBlueAdapter.getBondedDevices();
                    for (BluetoothDevice device: devices){
                        mPairedTv.append("\nDevice: " + device.getName()+ ", " + device);
                    }
                }
                else {
                    //bluetooth is off so can't get paired devices
                    showToast("Turn on bluetooth to get paired devices");
                }
            }
        });


    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        switch (requestCode){
            case REQUEST_ENABLE_BT:
                if (resultCode == RESULT_OK){
                    //bluetooth is on
                    mBlueIv.setImageResource(R.drawable.ic_launcher_foreground);
                    showToast("Bluetooth is on");
                }
                else {
                    //user denied to turn bluetooth on
                    showToast("could't on bluetooth");
                }
                break;
        }
        super.onActivityResult(requestCode, resultCode, data);
    }

    //toast message function
    private void showToast(String msg){
        Toast.makeText(this, msg, Toast.LENGTH_SHORT).show();
    }

}


<LinearLayout
    xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:tools="http://schemas.android.com/tools"
    xmlns:app="http://schemas.android.com/apk/res-auto"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:orientation="vertical"
    android:gravity="center_horizontal"
    tools:context=".MainActivity">

    <!--Display whether bluetooth is available or not-->
    <TextView
        android:id="@+id/statusBluetoothTv"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:text=""
        android:textAlignment="center"
        android:textSize="20sp"
        android:textColor="#000"/>
    <!--Bluetooth icon (on/off)-->
    <ImageView
        android:id="@+id/bluetoothIv"
        android:layout_width="100dp"
        android:layout_height="100dp" />
    <!--On Button-->
    <Button
        android:id="@+id/onBtn"
        android:minWidth="200dp"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:text="Turn On"
        style="@style/Base.Widget.AppCompat.Button.Colored"/>
    <!--Off btn-->
    <Button
        android:id="@+id/offBtn"
        android:minWidth="200dp"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:text="Turn Off"
        style="@style/Base.Widget.AppCompat.Button.Colored"/>
    <!--Discoverable button-->
    <Button
        android:id="@+id/discoverableBtn"
        android:minWidth="200dp"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:text="Discoverable"
        style="@style/Base.Widget.AppCompat.Button.Colored"/>
    <!--Get list of paired devices button-->
    <Button
        android:id="@+id/pairedBtn"
        android:minWidth="200dp"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:text="Get Paired Devices"
        style="@style/Base.Widget.AppCompat.Button.Colored"/>
    <!--Show paired devices here-->
    <TextView
        android:id="@+id/pairedTv"
        android:minWidth="200dp"
        android:text=""
        android:textColor="#000"
        android:layout_width="match_parent"
        android:layout_height="wrap_content" />

</LinearLayout>

?>



 
downlode image from webView
<?php


public class MainActivity extends AppCompatActivity {

    WebView mWebView;
    String mUrl = "https://devofandroid.blogspot.com/" ;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        mWebView = (WebView)findViewById(R.id.web_view);
        mWebView.getSettings().setJavaScriptEnabled(true);
        mWebView.setWebViewClient(new WebViewClient());

        registerForContextMenu(mWebView);

        mWebView.loadUrl(mUrl);
    }

    @Override
    public void onCreateContextMenu(ContextMenu contextMenu, View view, ContextMenu.ContextMenuInfo contextMenuInfo){
        super.onCreateContextMenu(contextMenu, view, contextMenuInfo);

        final WebView.HitTestResult webViewHitTestResult = mWebView.getHitTestResult();

        if (webViewHitTestResult.getType() == WebView.HitTestResult.IMAGE_TYPE ||
                webViewHitTestResult.getType() == WebView.HitTestResult.SRC_IMAGE_ANCHOR_TYPE) {

            contextMenu.setHeaderTitle("Download Image...");
            contextMenu.setHeaderIcon(R.drawable.ic_launcher_background);

            contextMenu.add(0, 1, 0, "Click to download")
                    .setOnMenuItemClickListener(new MenuItem.OnMenuItemClickListener() {
                        @Override
                        public boolean onMenuItemClick(MenuItem menuItem) {

                            String DownloadImageURL = webViewHitTestResult.getExtra();

                            if(URLUtil.isValidUrl(DownloadImageURL)){

                                DownloadManager.Request mRequest = new DownloadManager.Request(Uri.parse(DownloadImageURL));
                                mRequest.allowScanningByMediaScanner();
                                mRequest.setNotificationVisibility(DownloadManager.Request.VISIBILITY_VISIBLE_NOTIFY_COMPLETED);
                                DownloadManager mDownloadManager = (DownloadManager) getSystemService(DOWNLOAD_SERVICE);
                                mDownloadManager.enqueue(mRequest);

                                Toast.makeText(MainActivity.this,"Image Downloaded Successfully...",Toast.LENGTH_LONG).show();
                            }
                            else {
                                Toast.makeText(MainActivity.this,"Sorry.. Something Went Wrong...",Toast.LENGTH_LONG).show();
                            }
                            return false;
                        }
                    });
        }
    }
}








<LinearLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:app="http://schemas.android.com/apk/res-auto"
    xmlns:tools="http://schemas.android.com/tools"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:orientation="vertical"
    tools:context=".MainActivity">

    <WebView
        android:id="@+id/web_view"
        android:layout_width="match_parent"
        android:layout_height="match_parent" />

</LinearLayout>

?>




 
download file from imageview
<?php



public class MainActivity extends AppCompatActivity {

    WebView webView;
    String url = "https://drive.google.com/file/d/0B_rn9jkskDivX1dfZ3B3M2JVX2M/view?usp=drive_web";
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);


        //Runtime External storage permission for saving download files
        if (android.os.Build.VERSION.SDK_INT >= android.os.Build.VERSION_CODES.M) {
            if (checkSelfPermission(Manifest.permission.WRITE_EXTERNAL_STORAGE)
                    == PackageManager.PERMISSION_DENIED) {
                Log.d("permission", "permission denied to WRITE_EXTERNAL_STORAGE - requesting it");
                String[] permissions = {Manifest.permission.WRITE_EXTERNAL_STORAGE};
                requestPermissions(permissions, 1);
            }
        }


        webView = findViewById(R.id.web_view);
        webView.setWebViewClient(new WebViewClient());
        webView.getSettings().setLoadsImagesAutomatically(true);
        webView.getSettings().setJavaScriptEnabled(true);
        webView.setScrollBarStyle(View.SCROLLBARS_INSIDE_OVERLAY);
        webView.loadUrl(url);

        //handle downloading
        webView.setDownloadListener(new DownloadListener()
        {
            @Override
            public void onDownloadStart(String url, String userAgent,
                                        String contentDisposition, String mimeType,
                                        long contentLength) {
                DownloadManager.Request request = new DownloadManager.Request(
                        Uri.parse(url));
                request.setMimeType(mimeType);
                String cookies = CookieManager.getInstance().getCookie(url);
                request.addRequestHeader("cookie", cookies);
                request.addRequestHeader("User-Agent", userAgent);
                request.setDescription("Downloading File...");
                request.setTitle(URLUtil.guessFileName(url, contentDisposition, mimeType));
                request.allowScanningByMediaScanner();
                request.setNotificationVisibility(DownloadManager.Request.VISIBILITY_VISIBLE_NOTIFY_COMPLETED);
                request.setDestinationInExternalPublicDir(
                        Environment.DIRECTORY_DOWNLOADS, URLUtil.guessFileName(
                                url, contentDisposition, mimeType));
                DownloadManager dm = (DownloadManager) getSystemService(DOWNLOAD_SERVICE);
                dm.enqueue(request);
                Toast.makeText(getApplicationContext(), "Downloading File", Toast.LENGTH_LONG).show();
            }});

    }

}



<LinearLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:app="http://schemas.android.com/apk/res-auto"
    xmlns:tools="http://schemas.android.com/tools"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:orientation="vertical"
    tools:context=".MainActivity">

    <WebView
        android:id="@+id/web_view"
        android:layout_width="match_parent"
        android:layout_height="match_parent" />

</LinearLayout>


?>




 
click listview usefule to pdf reader
<?php


public class MainActivity extends AppCompatActivity {

    ListView list;
    String titles[] = {"Item 1", "Item 2", "Item 3", "Item 4", "Item 5"};
    String description[] = {"The Description 1", "The Description 2", "The Description 3", "The Description 4", "The Description 5"};
    int imgs[]={R.drawable.ic_launcher_background,R.drawable.ic_launcher_background,R.drawable.ic_launcher_background,R.drawable.ic_launcher_background, R.drawable.ic_launcher_background};

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);


        list = (ListView) findViewById(R.id.cus_list_view);

        MyAdapter adapter = new MyAdapter(this,titles,imgs,description);
        list.setAdapter(adapter);

        //click to go to another activity
        list.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                if (position == 0) {
                    Toast.makeText(MainActivity.this, "Item 1 clicked", Toast.LENGTH_SHORT).show();
                }
                else if (position == 1) {
                    Toast.makeText(MainActivity.this, "Item 2 clicked", Toast.LENGTH_SHORT).show();
                }
                else if (position == 2) {
                    Toast.makeText(MainActivity.this, "Item 3 clicked", Toast.LENGTH_SHORT).show();
                }
                else if (position == 3) {
                    Toast.makeText(MainActivity.this, "Item 4 clicked", Toast.LENGTH_SHORT).show();
                }
                else if (position == 4) {
                    Toast.makeText(MainActivity.this, "Item 5 clicked", Toast.LENGTH_SHORT).show();
                }

            }
        });
    }


    class MyAdapter extends ArrayAdapter<String> {

        Context context;
        String myTitles[];
        String myDescription[];
        int[] imgs;

        MyAdapter(Context c, String[] titles, int[] imgs, String[] description) {
            super(c,R.layout.row,R.id.text1,titles);
            this.context=c;
            this.imgs=imgs;
            this.myTitles=titles;
            this.myDescription=description;
        }

        @Override
        public View getView(int position, View convertView, ViewGroup parent)
        {
            LayoutInflater layoutInflater = (LayoutInflater)   getApplicationContext().getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            View row = layoutInflater.inflate(R.layout.row, parent, false);
            ImageView images = (ImageView) row.findViewById(R.id.logo);
            TextView myTitle = (TextView) row.findViewById(R.id.text1);
            TextView myDescription = (TextView) row.findViewById(R.id.text2);
            images.setImageResource(imgs[position]);
            myTitle.setText(titles[position]);
            myDescription.setText(description[position]);
            return row;
        }
	





mainactivity

<LinearLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:app="http://schemas.android.com/apk/res-auto"
    xmlns:tools="http://schemas.android.com/tools"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:orientation="vertical"
    tools:context=".MainActivity">

    <ListView
        android:id="@+id/cus_list_view"
        android:layout_width="match_parent"
        android:layout_height="match_parent" />

</LinearLayout>




row resource file

<LinearLayout xmlns:android="http://schemas.android.com/apk/res/android"
    android:orientation="horizontal"
    android:padding="4dp"
    android:layout_width="match_parent"
    android:layout_height="match_parent">

    <ImageView
        android:layout_width="60dp"
        android:layout_height="60dp"
        android:padding="5dp"
        android:id="@+id/logo"
        android:src="@mipmap/ic_launcher"/>

    <LinearLayout
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:orientation="vertical" >

        <TextView
            android:layout_width="wrap_content"
            android:layout_height="wrap_content"
            android:padding="2dp"
            android:textColor="#33CC33"
            android:id="@+id/text1"
            android:layout_marginTop="5dp"
            android:layout_marginLeft="10dp"
            android:text="Medium Text"/>

        <TextView
            android:layout_width="wrap_content"
            android:layout_height="wrap_content"
            android:id="@+id/text2"
            android:layout_marginLeft="10dp"
            android:text="TextView" />

    </LinearLayout>
</LinearLayout>
}
):}}?>

 
 
get sysem app in list view
<?php

public class MainActivity extends AppCompatActivity {

    private List<AppList> systemAppsList;
    private AppAdapter systemAppAdapter;
    ListView systemApsLv;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        systemApsLv = (ListView) findViewById(R.id.system_app_list);

        systemAppsList = getSystemApps();
        systemAppAdapter = new AppAdapter(MainActivity.this, systemAppsList);
        systemApsLv.setAdapter(systemAppAdapter);
        systemApsLv.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, final int i, long l) {

                String[] colors = {" Open App", " App Info"};
                AlertDialog.Builder builder = new AlertDialog.Builder(MainActivity.this);
                builder.setTitle("Choose Action")
                        .setItems(colors, new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int which) {
                                // The 'which' argument contains the index position of the selected item
                                if (which==0){
                                    //open app
                                    Intent intent = getPackageManager().getLaunchIntentForPackage(systemAppsList.get(i).packages);
                                    if(intent != null){
                                        startActivity(intent);
                                    }
                                    else {
                                        Toast.makeText(MainActivity.this, systemAppsList.get(i).packages + " Error, Please Try Again...", Toast.LENGTH_SHORT).show();
                                    }
                                }
                                //open info
                                if (which==1){
                                    Intent intent = new Intent(android.provider.Settings.ACTION_APPLICATION_DETAILS_SETTINGS);
                                    intent.setData(Uri.parse("package:" + systemAppsList.get(i).packages));
                                    Toast.makeText(MainActivity.this, systemAppsList.get(i).packages, Toast.LENGTH_SHORT).show();
                                    startActivity(intent);
                                }
                            }
                        });
                builder.show();


            }
        });

        //Total Number of System-Apps(i.e. List Size)
        String  abc = systemApsLv.getCount()+"";
        TextView countApps = (TextView)findViewById(R.id.countApps);
        countApps.setText("Total System Apps: "+abc);
        Toast.makeText(this, abc+" Apps", Toast.LENGTH_SHORT).show();

    }

    //get System userapps
    private List<AppList> getSystemApps() {
        PackageManager pm = getPackageManager();
        List<AppList> apps = new ArrayList<AppList>();
        List<PackageInfo> packs = getPackageManager().getInstalledPackages(0);
        //List<PackageInfo> packs = getPackageManager().getInstalledPackages(PackageManager.GET_PERMISSIONS);
        for (int i = 0; i < packs.size(); i++) {
            PackageInfo p = packs.get(i);
            if ((isSystemPackage(p))) {
                String appName = p.applicationInfo.loadLabel(getPackageManager()).toString();
                Drawable icon = p.applicationInfo.loadIcon(getPackageManager());
                String packages = p.applicationInfo.packageName;
                apps.add(new AppList(appName, icon, packages));
            }
        }
        return apps;
    }

    private boolean isSystemPackage(PackageInfo pkgInfo) {
        return (pkgInfo.applicationInfo.flags & ApplicationInfo.FLAG_SYSTEM) != 0;
    }

    public class AppAdapter extends BaseAdapter {

        public LayoutInflater layoutInflater;
        public List<AppList> listStorage;

        public AppAdapter(Context context, List<AppList> customizedListView) {
            layoutInflater =(LayoutInflater)context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            listStorage = customizedListView;
        }

        @Override
        public int getCount() {
            return listStorage.size();
        }

        @Override
        public Object getItem(int position) {
            return position;
        }

        @Override
        public long getItemId(int position) {
            return position;
        }

        @Override
        public View getView(int position, View convertView, ViewGroup parent) {
            int mLastPosition = 0;
            float initialTranslation = (mLastPosition <= position ? 500f : -500f);

            ViewHolder listViewHolder;
            if(convertView == null){
                listViewHolder = new ViewHolder();
                convertView = layoutInflater.inflate(R.layout.installed_app_list, parent, false);

                listViewHolder.textInListView = (TextView)convertView.findViewById(R.id.list_app_name);
                listViewHolder.imageInListView = (ImageView)convertView.findViewById(R.id.app_icon);
                listViewHolder.packageInListView=(TextView)convertView.findViewById(R.id.app_package);
                convertView.setTag(listViewHolder);
            }else{
                listViewHolder = (ViewHolder)convertView.getTag();
            }
            listViewHolder.textInListView.setText(listStorage.get(position).getName());
            listViewHolder.imageInListView.setImageDrawable(listStorage.get(position).getIcon());
            listViewHolder.packageInListView.setText(listStorage.get(position).getPackages());

            return convertView;
        }

        class ViewHolder{
            TextView textInListView;
            ImageView imageInListView;
            TextView packageInListView;
        }
    }

    public class AppList {
        private String name;
        Drawable icon;
        private String packages;
        public AppList(String name, Drawable icon, String packages) {
            this.name = name;
            this.icon = icon;
            this.packages = packages;
        }
        public String getName() {
            return name;
        }
        public Drawable getIcon() {
            return icon;
        }
        public String getPackages() {
            return packages;
        }

    }

}





}mainactivity


<LinearLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:app="http://schemas.android.com/apk/res-auto"
    xmlns:tools="http://schemas.android.com/tools"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:orientation="vertical"
    tools:context=".MainActivity">

    <TextView
        android:id="@+id/countApps"
        android:text="Count: "
        android:padding="5dp"
        android:layout_width="match_parent"
        android:layout_height="wrap_content" />
    <ListView
        android:id="@+id/system_app_list"
        android:layout_width="match_parent"
        android:layout_height="wrap_content" />

</LinearLayout>




list style
<LinearLayout xmlns:android="http://schemas.android.com/apk/res/android"
    android:layout_width="match_parent"
    android:layout_height="wrap_content"
    android:orientation="vertical"
    android:padding="3dp">
    <LinearLayout
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:orientation="horizontal"
        android:padding="5dp">
        <ImageView
            android:id="@+id/app_icon"
            android:layout_width="48dp"
            android:layout_height="48dp"/>
        <LinearLayout
            android:layout_marginLeft="3dp"
            android:layout_width="match_parent"
            android:layout_height="wrap_content"
            android:orientation="vertical"
            android:layout_marginStart="3dp">
            <TextView
                android:id="@+id/list_app_name"
                android:layout_width="match_parent"
                android:layout_height="wrap_content"
                android:textStyle="bold"
                android:textSize="16dp"
                android:text="App Name"/>
            <TextView
                android:id="@+id/app_package"
                android:text="App Package Name"
                android:layout_width="match_parent"
                android:layout_height="wrap_content" />
        </LinearLayout>
    </LinearLayout>


</LinearLayout>



?>



 
rate option
<?php


public class MainActivity extends AppCompatActivity {
    private RatingBar rBar;
    private TextView tView;
    private Button btn;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        rBar = (RatingBar) findViewById(R.id.ratingBar1);
        tView = (TextView) findViewById(R.id.textview1);
        btn = (Button)findViewById(R.id.btnGet);
        btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                int noofstars = rBar.getNumStars();
                float getrating = rBar.getRating();
                tView.setText("Rating: "+getrating+"/"+noofstars);
            }
        });
    }
}



<RelativeLayout xmlns:android="http://schemas.android.com/apk/res/android"
    android:layout_width="match_parent" android:layout_height="match_parent">
    <RatingBar
        android:id="@+id/ratingBar1"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:layout_marginLeft="80dp"
        android:layout_marginTop="200dp"
        android:numStars="5"
        android:rating="3.5"/>
    <Button
        android:id="@+id/btnGet"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:layout_alignLeft="@+id/ratingBar1"
        android:layout_below="@+id/ratingBar1"
        android:layout_marginTop="30dp"
        android:layout_marginLeft="60dp"
        android:text="Get Rating"/>
    <TextView
        android:id="@+id/textview1"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:layout_alignLeft="@+id/btnGet"
        android:layout_below="@+id/btnGet"
        android:layout_marginTop="20dp"
        android:textSize="20dp"
        android:textStyle="bold"/>
</RelativeLayout>
?>



 
to copy txt programly
<?php
  public void onClick(View view) {
                String s = mTextView.getText().toString();
                ClipboardManager cb = (ClipboardManager) getSystemService(Context.CLIPBOARD_SERVICE);
                cb.setText(s);
                Toast.makeText(getApplicationContext(), s+"\nCopied", Toast.LENGTH_SHORT).show();
            }


?>



 

<?php

jeson from url vollty

    public void loddata(View view) {
            String link="https://jsonplaceholder.typicode.com/posts";

        String url = "http://my-json-feed";
        StringRequest req=new StringRequest(link, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
        t.setText(response);
                Toast.makeText(MainActivity.this, response, Toast.LENGTH_SHORT).show();
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(MainActivity.this,error.toString(), Toast.LENGTH_SHORT).show();
                t.setText(error.toString());

            }
        }
        );

        Volley.newRequestQueue(this).add(req);




........................................................................

jeson web by retrofit



    public interface retr{
        @GET("posts")
        Call<ResponseBody> getpost();

    }
    public void ret(View view) {
        String url1 = "https://jsonplaceholder.typicode.com";
        Retrofit retrofit=new Retrofit.Builder().baseUrl(url1).build();
        retr r = retrofit.create(retr.class);
r.getpost().enqueue(new Callback<ResponseBody>() {
    @Override
    public void onResponse(Call<ResponseBody> call, retrofit2.Response<ResponseBody> response) {
        try {
            t.setText(response.body().string());
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    @Override
    public void onFailure(Call<ResponseBody> call, Throwable t) {
        Toast.makeText(MainActivity.this, t.toString(), Toast.LENGTH_SHORT).show();
    }
});
    }
	
	...................................................................
	

?>

  
