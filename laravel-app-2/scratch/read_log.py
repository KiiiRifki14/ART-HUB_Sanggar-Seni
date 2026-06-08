import json

with open(r'C:\Users\Muhammad_Rifki\.gemini\antigravity-ide\brain\f7cf88c8-a9b9-499a-ba13-f7a582d37ffe\.system_generated\logs\transcript.jsonl', 'r', encoding='utf-8') as f:
    for line in f:
        data = json.loads(line)
        if data.get('step_index') == 6183:
            print(data.get('content'))
            break
