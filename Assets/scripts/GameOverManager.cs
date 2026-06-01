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
        float highScore = PlayerPrefs.GetFloat(HighScoreKey, 0f);

        if (finalTime > highScore)
        {
            highScore = finalTime;
            PlayerPrefs.SetFloat(HighScoreKey, highScore);
            PlayerPrefs.Save();
        }

        finalTimeText.text = "Time: " + stopwatchTimer.FormatTime(finalTime);
        highScoreText.text = "Best: " + stopwatchTimer.FormatTime(highScore);

        gameOverPanel.SetActive(true);
    }

    public void RestartGame()
    {
        SceneManager.LoadScene(SceneManager.GetActiveScene().name);
    }
}