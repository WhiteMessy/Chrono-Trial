using UnityEngine;
using UnityEngine.Networking;
using System.Collections;

public class SupabaseManager : MonoBehaviour
{
    [Header("Supabase")]
    private string supabaseUrl =;
    private string apiKey =;

    public void UpdateCoins(int playerId, int coins)
    {
        StartCoroutine(UpdateCoinsRequest(playerId, coins));
    }

    private IEnumerator UpdateCoinsRequest(int playerId, int coins)
{
    string url = supabaseUrl + "/rest/v1/player_data";

    string json = "{\"id\":\"" + playerId + "\",\"coins\":" + coins + "}";

    UnityWebRequest request = new UnityWebRequest(url, "POST");
    byte[] bodyRaw = System.Text.Encoding.UTF8.GetBytes(json);

    request.uploadHandler = new UploadHandlerRaw(bodyRaw);
    request.downloadHandler = new DownloadHandlerBuffer();

    request.SetRequestHeader("Content-Type", "application/json");
    request.SetRequestHeader("apikey", apiKey);
    request.SetRequestHeader("Authorization", "Bearer " + apiKey);

    // IMPORTANT: allows update if row exists
    request.SetRequestHeader("Prefer", "resolution=merge-duplicates");

    yield return request.SendWebRequest();

    Debug.Log("Response code: " + request.responseCode);
    Debug.Log("Response body: " + request.downloadHandler.text);

    if (request.result == UnityWebRequest.Result.Success)
    {
        Debug.Log("Coins updated successfully");
    }
    else
    {
        Debug.LogError("Supabase error: " + request.error);
    }
}
}