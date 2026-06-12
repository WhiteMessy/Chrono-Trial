using TMPro;
using UnityEngine;
using UnityEngine.SceneManagement;

public class inlog : MonoBehaviour
{
    

    public void StartGame()
    {
        string username = "Test";

        Debug.Log("Username: " + username);

        // Save username
        PlayerPrefs.SetString("Username", username);

        // Load the next scene
        SceneManager.LoadScene("GameScene");
    }
}