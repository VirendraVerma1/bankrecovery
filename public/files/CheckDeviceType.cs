using JMRSDK.InputModule;
using UnityEngine;
using UnityEngine.Events;

public class CheckDeviceType : MonoBehaviour
{
    public UnityEvent onJioGlassPro;
    public UnityEvent onJioGlassLite;
    public UnityEvent onAndroid;
    public UnityEvent onEditor;
    public UnityEvent onPC;
    public UnityEvent onIOS;
    JMRInteractionManager.InteractionDeviceType deviceType; 
    void Start()
    {
           
        deviceType = JMRInteractionManager.Instance.GetSupportedInteractionDeviceType();
        if (deviceType == JMRInteractionManager.InteractionDeviceType.JIOGLASS_CONTROLLER) 
        {
            //Jio Glass Pro
            onJioGlassPro.Invoke();
        }
        else if (deviceType == JMRInteractionManager.InteractionDeviceType.VIRTUAL_CONTROLLER)
        {
            //Jio Glass Lite
            onJioGlassLite.Invoke();
        }
        else if (Application.isEditor)
        {
            //Editor
            onEditor.Invoke();
        }
        else if (Application.platform == RuntimePlatform.Android)
        {
            //Android
            onAndroid.Invoke();
        }
        else if (Application.platform == RuntimePlatform.IPhonePlayer)
        {
            //IOS
            onIOS.Invoke();
        }
        else if (Application.platform == RuntimePlatform.WindowsPlayer)
        {
            //PC
            onPC.Invoke();
        }
    }

    
}
