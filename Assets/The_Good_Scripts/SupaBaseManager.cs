using UnityEngine;
using UnityEngine.Networking;
using System.Collections;

public class SupabaseManager : MonoBehaviour
{
    [Header("Supabase")]


    [System.Serializable]
    public class PlayerData
    {
        public int Id;
        public string Username;
        public int coins;
        public float Time;
    }

    public Player player;

    [System.Serializable]
    public class PlayerDataArray
    {
        public PlayerData[] data;
    }

    // =========================
    // LOAD PLAYER DATA
    // =========================
    public void LoadCoins(string username)
    {
        StartCoroutine(LoadCoinsRequest(username));
    }

    private IEnumerator LoadCoinsRequest(string username)
{
    string url =
        supabaseUrl +
        "/rest/v1/leaderboard?Username=eq." +
        UnityWebRequest.EscapeURL(username) +
        "&select=*";

    UnityWebRequest request = UnityWebRequest.Get(url);

    request.SetRequestHeader("apikey", apiKey);
    request.SetRequestHeader("Authorization", "Bearer " + apiKey);

    yield return request.SendWebRequest();

    if (request.result == UnityWebRequest.Result.Success)
    {
        string json = request.downloadHandler.text;

        Debug.Log("Load response: " + json);

        json = "{\"data\":" + json + "}";

        PlayerDataArray loadedData =
            JsonUtility.FromJson<PlayerDataArray>(json);

        if (loadedData != null &&
            loadedData.data != null &&
            loadedData.data.Length > 0)
        {
            PlayerData playerData = loadedData.data[0];

            player.SetCoins(playerData.coins);
            player.playerId = playerData.Id;
            player.bestTime = playerData.Time;

            Debug.Log("Loaded OK");
        }
    }
    else
    {
        Debug.LogError("Load error: " + request.downloadHandler.text);
    }
}

    // =========================
    // UPSERT COINS
    // =========================
    public void UpdateCoins(int playerId, int coins)
    {
        StartCoroutine(UpdateCoinsRequest(playerId, coins));
    }

    private IEnumerator UpdateCoinsRequest(int playerId, int coins)
{
    string url =
        supabaseUrl +
        "/rest/v1/leaderboard?Id=eq." +
        playerId;

    string json =
        "{\"coins\":" + coins + "}";

    UnityWebRequest request =
        new UnityWebRequest(url, "PATCH");

    byte[] bodyRaw =
        System.Text.Encoding.UTF8.GetBytes(json);

    request.uploadHandler = new UploadHandlerRaw(bodyRaw);
    request.downloadHandler = new DownloadHandlerBuffer();

    request.SetRequestHeader("Content-Type", "application/json");
    request.SetRequestHeader("apikey", apiKey);
    request.SetRequestHeader("Authorization", "Bearer " + apiKey);

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

    // =========================
    // UPDATE BEST TIME
    // =========================
    public void UpdateBestTime(int playerId, float bestTime)
    {
        Debug.Log("UpdateBestTime called");
        StartCoroutine(UpdateBestTimeRequest(playerId, bestTime));
    }

    private IEnumerator UpdateBestTimeRequest(int playerId, float bestTime)
    {
        string url =
            supabaseUrl +
            "/rest/v1/leaderboard?Id=eq." +
            playerId;

        string json = "{\"Time\":" + bestTime + "}";

        Debug.Log("PATCH URL: " + url);
        Debug.Log("Best Time Value: " + bestTime);

        UnityWebRequest request =
            new UnityWebRequest(url, "PATCH");

        byte[] bodyRaw =
            System.Text.Encoding.UTF8.GetBytes(json);

        request.uploadHandler = new UploadHandlerRaw(bodyRaw);
        request.downloadHandler = new DownloadHandlerBuffer();

        request.SetRequestHeader("Content-Type", "application/json");
        request.SetRequestHeader("apikey", apiKey);
        request.SetRequestHeader("Authorization", "Bearer " + apiKey);

        yield return request.SendWebRequest();

        Debug.Log("Best Time Response: " + request.downloadHandler.text);

        if (request.result == UnityWebRequest.Result.Success)
        {
            Debug.Log("Best time saved: " + bestTime);
        }
        else
        {
            Debug.LogError("Best time error: " + request.error);
        }
    }
}
