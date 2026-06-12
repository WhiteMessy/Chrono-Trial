using UnityEngine;
using UnityEngine.SceneManagement;
using TMPro;

public class GameOverManager : MonoBehaviour
{
    [Header("UI")]
    public GameObject gameOverPanel;
    public TMP_Text finalTimeText;
    public TMP_Text highScoreText;

    [Header("References")]
    public StopwatchTimer stopwatchTimer;
    public MonoBehaviour playerMovement;
    public AutoScroll autoScroll;

    private const string HighScoreKey = "HighScore";

    void Start()
    {
        gameOverPanel.SetActive(false);
    }

    public void GameOver()
{
    stopwatchTimer.StopTimer();

    if (playerMovement != null)
    {
        playerMovement.enabled = false;
    }

    float finalTime = stopwatchTimer.GetTime();

    Player player = FindFirstObjectByType<Player>();

    float highScore = 0f;

    if (player != null)
    {
        highScore = player.bestTime;

        if (finalTime > highScore)
        {
            highScore = finalTime;
            player.bestTime = highScore;

            if (player.supabase != null)
            {
                Debug.Log("Saving best time: " + highScore);
                Debug.Log("Player ID: " + player.playerId);

                player.supabase.UpdateBestTime(
                    player.playerId,
                    highScore
                );
            }
        }
    }

    finalTimeText.text =
        "Time: " + stopwatchTimer.FormatTime(finalTime);

    highScoreText.text =
        "Best: " + stopwatchTimer.FormatTime(highScore);

    gameOverPanel.SetActive(true);

    if (autoScroll != null)
    {
        autoScroll.scrollingActive = false;
    }
}

    public void RestartGame()
    {
        SceneManager.LoadScene(SceneManager.GetActiveScene().name);
    }
}
