#!/bin/bash

RECORD_DIR="/mnt/stt_laptop"

echo "========================================"
echo "        STT DATA COLLECTION"
echo "========================================"
echo ""

read -p "Participant ID (e.g. P001): " PARTICIPANT
read -p "Activity (e.g. word): " ACTIVITY

NUMBER=1

while true
do
    FILENAME="${PARTICIPANT}_${ACTIVITY}_$(printf "%03d" $NUMBER).wav"
    FILEPATH="$RECORD_DIR/$FILENAME"

    echo ""
    echo "----------------------------------------"
    echo "Participant : $PARTICIPANT"
    echo "Activity    : $ACTIVITY"
    echo "Recording   : $(printf "%03d" $NUMBER)"
    echo "File        : $FILENAME"
    echo "----------------------------------------"
    echo ""
    echo "[ENTER] Start recording"
    echo "[N]     Next recording number"
    echo "[E]     End session"
    echo ""

    read -n 1 -s KEY

    if [[ "$KEY" == "e" || "$KEY" == "E" ]]; then
        echo ""
        echo "Session ended."
        break
    fi

    if [[ "$KEY" == "n" || "$KEY" == "N" ]]; then
        NUMBER=$((NUMBER + 1))
        continue
    fi

    echo ""
    echo "🔴 RECORDING..."
    echo "Press CTRL+C to stop."
    echo ""

    arecord \
    -D plughw:2,0 \
    -c 1 \
    -r 48000 \
    -f S32_LE \
    -t wav \
    "$FILEPATH"

    echo ""
    echo "✅ Saved: $FILENAME"
    echo ""
    echo "[ENTER] Continue to next recording"
    echo "[R]     Retake this recording"
    echo "[E]     End session"

    read -n 1 -s ACTION

    if [[ "$ACTION" == "r" || "$ACTION" == "R" ]]; then
        rm -f "$FILEPATH"
        echo "↩️ Retake selected."
        continue
    fi

    if [[ "$ACTION" == "e" || "$ACTION" == "E" ]]; then
        echo ""
        echo "Session ended."
        break
    fi

    NUMBER=$((NUMBER + 1))
done
