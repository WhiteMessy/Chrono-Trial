using System.Collections;
using TMPro;
using UnityEngine;

public class CountdownManager : MonoBehaviour
{
    public TMP_Text countdownText;
    public Player playerMovement;
    public StopwatchTimer stopwatchTimer;
    public AutoScroll autoScroll;

    void Start()
    {
        StartCoroutine(StartCountdown());
    }

    IEnumerator StartCountdown()
    {
        playerMovement.enabled = false;

        countdownText.text = "3";
        yield return new WaitForSeconds(1f);

        countdownText.text = "2";
        yield return new WaitForSeconds(1f);

        countdownText.text = "1";
        yield return new WaitForSeconds(1f);

        countdownText.text = "GO!";

        playerMovement.enabled = true;
        stopwatchTimer.timerRunning = true;
        autoScroll.scrollingActive = true;

        yield return new WaitForSeconds(0.5f);

        countdownText.gameObject.SetActive(false);
    }
}