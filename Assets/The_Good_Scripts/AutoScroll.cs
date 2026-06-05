using UnityEngine;

public class AutoScroll : MonoBehaviour
{
    public float scrollSpeed = 2f;
    public bool scrollingActive = false;

    [Header("Height Tracking")]
    public Transform player;
    public float ySmoothSpeed = 5f;

    void Update()
    {
        Vector3 newPosition = transform.position;

        if (scrollingActive)
        {
            newPosition.x += scrollSpeed * Time.deltaTime;
        }

        if (player != null)
        {
            newPosition.y = Mathf.Lerp(
                transform.position.y,
                player.position.y,
                ySmoothSpeed * Time.deltaTime
            );
        }

        transform.position = newPosition;
    }
}