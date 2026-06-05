using UnityEngine;
using UnityEngine.Networking;
using System.Collections;


public class SupabaseManager : MonoBehaviour
{
    [Header("Supabase")]
    private string supabaseUrl = "";
    private string apiKey = "secret";

    [System.Serializable]
    public class PlayerData
    {
        public int id;
        public int coins;
    }
    public Player player;

    // Used because Supabase returns an array
    [System.Serializable]
    public class PlayerDataArray
    {
        public PlayerData[] data;
    }

    // LOAD COINS
    public void LoadCoins(int playerId)
    {
        StartCoroutine(LoadCoinsRequest(playerId));
    }

    private IEnumerator LoadCoinsRequest(int playerId)
{
    string url =
        supabaseUrl +
        "/rest/v1/player_data?id=eq." +
        playerId +
        "&select=*";

    UnityWebRequest request = UnityWebRequest.Get(url);

    request.SetRequestHeader("apikey", apiKey);
    request.SetRequestHeader("Authorization", "Bearer " + apiKey);

    yield return request.SendWebRequest();

    if (request.result == UnityWebRequest.Result.Success)
    {
        string json = request.downloadHandler.text;

        Debug.Log("Load response: " + json);

        // Supabase returns:
        // [{"id":1,"coins":3}]
        // JsonUtility needs:
        // {"data":[{"id":1,"coins":3}]}

        json = "{\"data\":" + json + "}";

        PlayerDataArray loadedData =
            JsonUtility.FromJson<PlayerDataArray>(json);

        if (loadedData != null &&
            loadedData.data != null &&
            loadedData.data.Length > 0)
        {
            int loadedCoins =
                loadedData.data[0].coins;

            Debug.Log("Loaded coins: " + loadedCoins);

            if (player != null)
            {
                player.SetCoins(loadedCoins);
            }
            else
            {
                Debug.LogError(
                    "Player reference not assigned in SupabaseManager!"
                );
            }
        }
    }
    else
    {
        Debug.LogError("Load error: " + request.error);
    }
}

    // SAVE COINS
    public void UpdateCoins(int playerId, int coins)
    {
        StartCoroutine(UpdateCoinsRequest(playerId, coins));
    }

    private IEnumerator UpdateCoinsRequest(int playerId, int coins)
    {
        string url = supabaseUrl + "/rest/v1/player_data";

        // id is an int, so NO quotes around playerId
        string json =
            "{\"id\":" + playerId +
            ",\"coins\":" + coins + "}";

        UnityWebRequest request = new UnityWebRequest(url, "POST");

        byte[] bodyRaw =
            System.Text.Encoding.UTF8.GetBytes(json);

        request.uploadHandler =
            new UploadHandlerRaw(bodyRaw);

        request.downloadHandler =
            new DownloadHandlerBuffer();

        request.SetRequestHeader(
            "Content-Type",
            "application/json"
        );

        request.SetRequestHeader(
            "apikey",
            apiKey
        );

        request.SetRequestHeader(
            "Authorization",
            "Bearer " + apiKey
        );

        request.SetRequestHeader(
            "Prefer",
            "resolution=merge-duplicates"
        );

        yield return request.SendWebRequest();

        Debug.Log(
            "Response code: " +
            request.responseCode
        );

        Debug.Log(
            "Response body: " +
            request.downloadHandler.text
        );

        if (request.result ==
            UnityWebRequest.Result.Success)
        {
            Debug.Log(
                "Coins updated successfully"
            );
        }
        else
        {
            Debug.LogError(
                "Supabase error: " +
                request.error
            );
        }
    }
}