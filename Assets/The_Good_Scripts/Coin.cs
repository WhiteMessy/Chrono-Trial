using UnityEngine;
using TMPro;

public class Coin : MonoBehaviour
{
    private TextMeshProUGUI coinText;

    private void Start()
    {
        Debug.Log("Coin started: " + gameObject.name);

        coinText = GameObject.FindWithTag("CoinText").GetComponent<TextMeshProUGUI>();
    }

    private void OnTriggerEnter2D(Collider2D collision)
    {
        Debug.Log("Coin triggered by: " + collision.name);

        if (collision.gameObject.tag == "Player")
        {
            Debug.Log("Player detected");

            Player player = collision.gameObject.GetComponent<Player>();

            if (player == null)
            {
                Debug.LogError("Player script is NULL on Player object!");
                return;
            }

            player.coins += 1;
            Debug.Log("Coins now: " + player.coins);

            coinText.text = player.coins.ToString();

            Debug.Log("Calling SaveCoins()");
            player.SaveCoins();

            if (player.supabase != null)
            {
                Debug.Log("Supabase found, sending update...");
                player.supabase.UpdateCoins(player.playerId, player.coins);
            }
            else
            {
                Debug.LogError("Supabase is NULL on Player!");
            }

            Destroy(gameObject);
        }
    }
}